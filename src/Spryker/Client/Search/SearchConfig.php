<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Shared\Search\SearchConstants;

/**
 * @method \Spryker\Shared\Search\SearchConfig getSharedConfig()
 */
class SearchConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    public const FACET_NAME_AGGREGATION_SIZE = 10;

    /**
     * @var string
     */
    protected const DEFAULT_SOURCE_IDENTIFIER = 'page';

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @api
     *
     * @deprecated Use source identifiers instead.
     *
     * @return string
     */
    public function getSearchIndexName()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__INDEX_NAME, sprintf('%s_search', strtolower($this->getCurrentStore())));
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchConfig::getDefaultSourceIdentifier()} instead for vendor specific source identification.
     *
     * @return string
     */
    public function getSearchDocumentType()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE, 'page');
    }

    /**
     * @api
     *
     * @return array
     */
    public function getElasticsearchConfig()
    {
        $config = $this->get(SearchConstants::ELASTICA_CLIENT_CONFIGURATION, null);
        if ($config !== null) {
            return $config;
        }

        $config = $this->get(SearchConstants::ELASTICA_PARAMETER__EXTRA, null);
        if ($config === null) {
            $config = [];
        }

        $config['transport'] = ucfirst($this->get(SearchConstants::ELASTICA_PARAMETER__TRANSPORT));
        $config['port'] = $this->get(SearchConstants::ELASTICA_PARAMETER__PORT);
        $config['host'] = $this->get(SearchConstants::ELASTICA_PARAMETER__HOST);

        $authHeader = (string)$this->get(SearchConstants::ELASTICA_PARAMETER__AUTH_HEADER, '');

        if ($authHeader !== '') {
            $config['headers'] = [
                'Authorization' => 'Basic ' . $authHeader,
            ];
        }

        return $config;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getFacetNameAggregationSize()
    {
        return static::FACET_NAME_AGGREGATION_SIZE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultSourceIdentifier(): string
    {
        return static::DEFAULT_SOURCE_IDENTIFIER;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled
     *
     * @return string
     */
    protected function getCurrentStore(): string
    {
        $container = (new GlobalContainer())->getContainer();
        if ($container->has(static::SERVICE_STORE)) {
            return $container->get(static::SERVICE_STORE);
        }

        return APPLICATION_STORE;
    }
}
