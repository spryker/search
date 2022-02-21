<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Generator;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * @deprecated Will be removed without replacement.
 */
class IndexMapGenerator implements IndexMapGeneratorInterface
{
    /**
     * @var string
     */
    public const TWIG_TEMPLATES_LOCATION = '/Templates/';

    /**
     * @var string
     */
    public const CLASS_NAME_SUFFIX = 'IndexMap';

    /**
     * @var string
     */
    public const CLASS_EXTENSION = '.php';

    /**
     * @var string
     */
    public const PROPERTIES = 'properties';

    /**
     * @var string
     */
    public const PROPERTY_PATH_SEPARATOR = '.';

    /**
     * @var string
     */
    public const TEMPLATE_VARIABLE_CLASS_NAME = 'className';

    /**
     * @var string
     */
    public const TEMPLATE_VARIABLE_CONSTANTS = 'constants';

    /**
     * @var string
     */
    public const TEMPLATE_VARIABLE_METADATA = 'metadata';

    /**
     * @var string
     */
    protected $targetBaseDirectory;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var int
     */
    protected $permissionMode;

    /**
     * @param string $targetDirectory
     * @param int $permissionMode
     */
    public function __construct(string $targetDirectory, int $permissionMode)
    {
        $this->targetBaseDirectory = rtrim($targetDirectory, '/') . '/';
        $this->permissionMode = $permissionMode;

        $loader = new FilesystemLoader(__DIR__ . static::TWIG_TEMPLATES_LOCATION);
        $this->twig = new Environment($loader, []);
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinition
     *
     * @return void
     */
    public function generate(ElasticsearchIndexDefinitionTransfer $indexDefinition)
    {
        foreach ($indexDefinition->getMappings() as $mappingName => $mapping) {
            $mappingName = $this->normalizeToClassName($mappingName);
            $this->generateIndexMapClass($mappingName, $mapping);
        }
    }

    /**
     * @param string $mappingName
     *
     * @return string
     */
    protected function normalizeToClassName($mappingName)
    {
        $normalized = preg_replace('/\\W+/', '_', $mappingName);
        $normalized = trim($normalized, '_');

        $filter = new UnderscoreToCamelCase();
        /** @var string $normalized */
        $normalized = $filter->filter($normalized);
        $normalized = ucfirst($normalized);

        return $normalized;
    }

    /**
     * @param string $mappingName
     * @param array $mapping
     *
     * @return void
     */
    protected function generateIndexMapClass($mappingName, array $mapping)
    {
        $fileName = $mappingName . static::CLASS_NAME_SUFFIX . static::CLASS_EXTENSION;
        $templateData = $this->getTemplateData($mappingName, $mapping);
        $fileContent = $this->twig->render('class.php.twig', $templateData);

        if (!is_dir($this->targetBaseDirectory)) {
            mkdir($this->targetBaseDirectory, $this->permissionMode, true);
        }

        file_put_contents($this->targetBaseDirectory . $fileName, $fileContent);
    }

    /**
     * @param string $mappingName
     * @param array $mapping
     *
     * @return array
     */
    protected function getTemplateData($mappingName, array $mapping)
    {
        $properties = $this->getMappingProperties($mapping);

        return [
            static::TEMPLATE_VARIABLE_CLASS_NAME => $mappingName . static::CLASS_NAME_SUFFIX,
            static::TEMPLATE_VARIABLE_CONSTANTS => $this->getConstants($properties),
            static::TEMPLATE_VARIABLE_METADATA => $this->getMetadata($properties),
        ];
    }

    /**
     * @param array $properties
     * @param string|null $path
     *
     * @return array
     */
    protected function getConstants(array $properties, $path = null)
    {
        $constants = [];

        foreach ($properties as $propertyName => $propertyData) {
            $propertyConstantName = $this->convertToConstant($path . $propertyName);

            $constants[$propertyConstantName] = $path . $propertyName;

            $constants = $this->getChildConstants($path, $propertyData, $propertyName, $constants);
        }

        return $constants;
    }

    /**
     * @param array $properties
     * @param string|null $path
     *
     * @return array
     */
    protected function getMetadata(array $properties, $path = null)
    {
        $metadata = [];

        foreach ($properties as $propertyName => $propertyData) {
            $propertyConstantName = $this->convertToConstant($path . $propertyName);

            $metadata = $this->getScalarMetadata($propertyData, $metadata, $propertyConstantName);

            $metadata = $this->getChildMetadata($path, $propertyData, $propertyName, $metadata);
        }

        return $metadata;
    }

    /**
     * @param array $mapping
     *
     * @return array
     */
    protected function getMappingProperties(array $mapping)
    {
        return $mapping[static::PROPERTIES] ?? [];
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function convertToConstant($string)
    {
        $normalized = preg_replace('/\\W+/', '_', $string);
        $normalized = trim($normalized, '_');
        $normalized = mb_strtoupper($normalized);

        return $normalized;
    }

    /**
     * @param array $propertyData
     * @param array $metadata
     * @param string $propertyConstantName
     *
     * @return array
     */
    protected function getScalarMetadata(array $propertyData, array $metadata, $propertyConstantName)
    {
        foreach ($propertyData as $key => $value) {
            if (is_scalar($value)) {
                $metadata[$propertyConstantName][$key] = $value;
            }
        }

        return $metadata;
    }

    /**
     * @param string $path
     * @param array $propertyData
     * @param string $propertyName
     * @param array $metadata
     *
     * @return array
     */
    protected function getChildMetadata($path, array $propertyData, $propertyName, array $metadata)
    {
        if (!isset($propertyData[static::PROPERTIES])) {
            return $metadata;
        }

        $path .= $propertyName . static::PROPERTY_PATH_SEPARATOR;

        $childMetadata = $this->getMetadata($propertyData[static::PROPERTIES], $path);

        $metadata = array_merge($metadata, $childMetadata);

        return $metadata;
    }

    /**
     * @param string $path
     * @param array $propertyData
     * @param string $propertyName
     * @param array $constants
     *
     * @return array
     */
    protected function getChildConstants($path, array $propertyData, $propertyName, array $constants)
    {
        if (!isset($propertyData[static::PROPERTIES])) {
            return $constants;
        }

        $path .= $propertyName . static::PROPERTY_PATH_SEPARATOR;

        $childMetadata = $this->getConstants($propertyData[static::PROPERTIES], $path);

        $constants = array_merge($constants, $childMetadata);

        return $constants;
    }
}
