<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Model\Elasticsearch\Generator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapCleaner;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Generator\IndexMapGenerator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group Elasticsearch
 * @group Generator
 * @group IndexMapClassGeneratorTest
 * Add your own group annotations below this line
 */
class IndexMapClassGeneratorTest extends Unit
{
    public const TARGET_DIRECTORY = __DIR__ . '/Generated/';

    public const TEST_FILES_DIRECTORY = __DIR__ . '/test_files/';

    public function tearDown(): void
    {
        $searchMapCleaner = new IndexMapCleaner(static::TARGET_DIRECTORY);
        $searchMapCleaner->cleanDirectory();
    }

    public function testGenerateSimpleIndexMap(): void
    {
        $generator = new IndexMapGenerator(static::TARGET_DIRECTORY, 0777);

        $indexDefinition = $this->createIndexDefinition('index1', [], [
            'simple' => [
                'properties' => [
                    'foo' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    'bar' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                    'baz' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                    ],
                ],
            ],
        ]);

        $generator->generate($indexDefinition);

        $this->assertFileEquals(
            static::TEST_FILES_DIRECTORY . 'SimpleIndexMap.expected.php',
            static::TARGET_DIRECTORY . 'SimpleIndexMap.php',
        );
    }

    public function testGenerateComplexIndexMap(): void
    {
        $generator = new IndexMapGenerator(static::TARGET_DIRECTORY, 0777);

        $indexDefinition = $this->createIndexDefinition('index-1', [], [
            'complex' => [
                'properties' => [
                    'foo' => [
                        'a' => 'asdf',
                        'b' => 'qwer',
                        'properties' => [
                            'bar' => [
                                'a' => 'asdf',
                                'b' => 'qwer',
                                'properties' => [
                                    'baz' => [
                                        'a' => 'asdf',
                                        'b' => 'qwer',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $generator->generate($indexDefinition);

        $this->assertFileEquals(
            static::TEST_FILES_DIRECTORY . 'ComplexIndexMap.expected.php',
            static::TARGET_DIRECTORY . 'ComplexIndexMap.php',
        );
    }

    public function testGenerateMultiFieldIndexMap(): void
    {
        $generator = new IndexMapGenerator(static::TARGET_DIRECTORY, 0777);

        $indexDefinition = $this->createIndexDefinition('index-1', [], [
            'multi-field' => [
                'properties' => [
                    'full-text' => [
                        'search_analyzer' => 'ja_kuromoji_search_analyzer',
                        'analyzer' => 'ja_kuromoji_index_analyzer',
                        'fields' => [
                            'ngram' => [
                                'type' => 'text',
                                'search_analyzer' => 'ja_ngram_search_analyzer',
                                'analyzer' => 'ja_ngram_index_analyzer',
                            ],
                        ],
                    ],
                    'full-text-boosted' => [
                        'search_analyzer' => 'ja_kuromoji_search_analyzer',
                        'analyzer' => 'ja_kuromoji_index_analyzer',
                        'fields' => [
                            'ngram' => [
                                'type' => 'text',
                                'search_analyzer' => 'ja_ngram_search_analyzer',
                                'analyzer' => 'ja_ngram_index_analyzer',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $generator->generate($indexDefinition);

        $this->assertFileEquals(
            static::TEST_FILES_DIRECTORY . 'MultiFieldIndexMap.expected.php',
            static::TARGET_DIRECTORY . 'MultiFieldIndexMap.php',
        );
    }

    protected function createIndexDefinition(string $name, array $settings = [], array $mappings = []): ElasticsearchIndexDefinitionTransfer
    {
        $indexDefinition = new ElasticsearchIndexDefinitionTransfer();
        $indexDefinition
            ->setIndexName($name)
            ->setSettings($settings)
            ->setMappings($mappings);

        return $indexDefinition;
    }
}
