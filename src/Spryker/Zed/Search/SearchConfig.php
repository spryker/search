<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search;

use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Search\SearchConfig getSharedConfig()
 */
class SearchConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::HOST
     *
     * @var string
     */
    protected const HOST = 'SEARCH_ELASTICSEARCH:HOST';

    /**
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::PORT
     *
     * @var string
     */
    protected const PORT = 'SEARCH_ELASTICSEARCH:PORT';

    /**
     * @var array<string>
     */
    protected const BLACKLIST_SETTINGS_FOR_INDEX_UPDATE = [
        'index.number_of_shards',
        'index.routing_partition_size',
    ];

    /**
     * @var array<string>
     */
    protected const STATIC_INDEX_SETTINGS = [
        'index.number_of_shards',
        'index.shard.check_on_startup',
        'index.codec',
        'index.routing_partition_size',
        'analysis',
    ];

    /**
     * @var array<string>
     */
    protected const DYNAMIC_INDEX_SETTINGS = [
        'index.number_of_replicas',
        'index.auto_expand_replicas',
        'index.refresh_interval',
        'index.max_result_window',
        'index.max_inner_result_window',
        'index.max_rescore_window',
        'index.max_docvalue_fields_search',
        'index.max_script_fields',
        'index.max_ngram_diff',
        'index.max_shingle_diff',
        'index.blocks.read_only',
        'index.blocks.read_only_allow_delete',
        'index.blocks.read',
        'index.blocks.write',
        'index.blocks.metadata',
        'index.max_refresh_listeners',
        'index.highlight.max_analyzed_offset',
        'index.max_terms_count',
        'index.routing.allocation.enable',
        'index.routing.rebalance.enable',
        'index.gc_deletes',
    ];

    /**
     * @var string
     */
    public const INDEX_OPEN_STATE = 'open';

    /**
     * @var string
     */
    public const INDEX_CLOSE_STATE = 'close';

    /**
     * @api
     *
     * @deprecated Will be removed without replacement. The type will be resolved based on the source identifier, provider for search.
     *
     * @return string
     */
    public function getElasticaDocumentType()
    {
        return $this->get(SearchConstants::ELASTICA_PARAMETER__DOCUMENT_TYPE, 'page');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getReindexUrl()} instead.
     *
     * @return string
     */
    public function getReindexUrl()
    {
        return sprintf(
            '%s:%s/_reindex?pretty',
            $this->get(SearchConstants::ELASTICA_PARAMETER__HOST, $this->get(static::HOST)),
            $this->get(SearchConstants::ELASTICA_PARAMETER__PORT, $this->get(static::PORT)),
        );
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getJsonSchemaDefinitionDirectories()} instead.
     *
     * @return array<string>
     */
    public function getJsonIndexDefinitionDirectories()
    {
        $directories = [
            $this->getSprykerRootDir() . '/*/src/*/Shared/*/IndexMap/',
        ];

        $applicationTransferGlobPattern = APPLICATION_SOURCE_DIR . '/*/Shared/*/IndexMap/';
        if (glob($applicationTransferGlobPattern, GLOB_NOSORT | GLOB_ONLYDIR)) {
            $directories[] = $applicationTransferGlobPattern;
        }

        return $directories;
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getClassTargetDirectory()} instead.
     *
     * @return string
     */
    public function getClassTargetDirectory()
    {
        return APPLICATION_SOURCE_DIR . '/Generated/Shared/Search/';
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getBlacklistSettingsForIndexUpdate()} instead.
     *
     * @return array<string>
     */
    public function getBlacklistSettingsForIndexUpdate(): array
    {
        return static::BLACKLIST_SETTINGS_FOR_INDEX_UPDATE;
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getStaticIndexSettings()} instead.
     *
     * @return array<string>
     */
    public function getStaticIndexSettings()
    {
        return static::STATIC_INDEX_SETTINGS;
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getDynamicIndexSettings()} instead.
     *
     * @return array<string>
     */
    public function getDynamicIndexSettings()
    {
        return static::DYNAMIC_INDEX_SETTINGS;
    }

    /**
     * @return string
     */
    protected function getSprykerRootDir()
    {
        /** @phpstan-var string */
        return realpath(__DIR__ . '/../../../../../');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getPermissionMode()} instead.
     *
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(SearchConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig::getIndexMapClassTemplateDirectory()} instead.
     *
     * @return string
     */
    public function getIndexMapClassTemplateDirectory(): string
    {
        return __DIR__ . '/Business/Installer/IndexMap/Generator/Templates/';
    }

    /**
     * @api
     *
     * @deprecated The index suffix will be resolved by {@link \Spryker\Client\SearchElasticsearch\SearchContextExpander\SearchContextExpanderInterface}
     *   implementation in Elasticsearch specific vendor module.
     *
     * @return string
     */
    public function getIndexNameSuffix(): string
    {
        return $this->get(SearchConstants::SEARCH_INDEX_NAME_SUFFIX, '');
    }
}
