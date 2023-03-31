<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Search\Delegator\Adapter\SearchDelegatorAdapter;
use Spryker\Client\Search\Delegator\Adapter\SearchDelegatorAdapterInterface;
use Spryker\Client\Search\Delegator\ConnectionDelegator;
use Spryker\Client\Search\Delegator\ConnectionDelegatorInterface;
use Spryker\Client\Search\Delegator\SearchDelegator;
use Spryker\Client\Search\Delegator\SearchDelegatorInterface;
use Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientInterface;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactory;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactory;
use Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Query\QueryFactory;
use Spryker\Client\Search\Model\Elasticsearch\Reader\Reader;
use Spryker\Client\Search\Model\Elasticsearch\Suggest\SuggestBuilder;
use Spryker\Client\Search\Model\Elasticsearch\Writer\Writer;
use Spryker\Client\Search\Model\Handler\ElasticsearchSearchHandler;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Client\Search\Plugin\Config\PaginationConfigBuilder;
use Spryker\Client\Search\Plugin\Config\SearchConfig;
use Spryker\Client\Search\Plugin\Config\SortConfigBuilder;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchKeysQuery;
use Spryker\Client\Search\Plugin\Elasticsearch\Query\SearchStringQuery;
use Spryker\Client\Search\Provider\IndexClientProvider;
use Spryker\Client\Search\Provider\SearchClientProvider;
use Spryker\Client\Search\SearchContext\SearchContextExpander;
use Spryker\Client\Search\SearchContext\SearchContextExpanderInterface;

/**
 * @method \Spryker\Client\Search\SearchConfig getConfig()
 */
class SearchFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    protected static $searchConfigInstance;

    /**
     * @var \Elastica\Client
     */
    protected static $searchClient;

    /**
     * @return \Spryker\Client\Search\Delegator\SearchDelegatorInterface
     */
    public function createSearchDelegator(): SearchDelegatorInterface
    {
        return new SearchDelegator(
            $this->getClientAdapterPlugins(),
            $this->createSearchContextExpander(),
        );
    }

    /**
     * @return \Spryker\Client\Search\Delegator\ConnectionDelegatorInterface
     */
    public function createConnectionDelegator(): ConnectionDelegatorInterface
    {
        return new ConnectionDelegator(
            $this->getClientAdapterPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Delegator\Adapter\SearchDelegatorAdapterInterface
     */
    public function createSearchDelegatorAdapter(): SearchDelegatorAdapterInterface
    {
        return new SearchDelegatorAdapter($this->createSearchDelegator(), $this->getConfig());
    }

    /**
     * @return \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface
     */
    public function createSearchContextExpander(): SearchContextExpanderInterface
    {
        return new SearchContextExpander(
            $this->getSearchContextExpanderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\ConnectionCheckerAdapterPluginInterface>
     */
    public function getClientAdapterPlugins(): array
    {
        return $this->getProvidedDependency(SearchDependencyProvider::PLUGINS_CLIENT_ADAPTER);
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface>
     */
    public function getSearchContextExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SearchDependencyProvider::PLUGINS_SEARCH_CONTEXT_EXPANDER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        if (static::$searchConfigInstance === null) {
            static::$searchConfigInstance = $this->createSearchConfig();
        }

        return static::$searchConfigInstance;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function createSearchConfig()
    {
        return new SearchConfig();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface
     */
    public function getSearchConfigBuilder()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_CONFIG_BUILDER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Elastica\Client
     */
    public function getElasticsearchClient()
    {
        /** @var \Elastica\Client $client */
        $client = $this->createSearchClientProvider()->getInstance();

        return $client;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Provider\SearchClientProvider
     */
    protected function createSearchClientProvider()
    {
        return new SearchClientProvider();
    }

    /**
     * @deprecated Use {@link \Spryker\Client\Search\SearchFactory::createSearchDelegator()} instead.
     *
     * @return \Spryker\Client\Search\Model\Handler\SearchHandlerInterface|\Spryker\Client\Search\Delegator\SearchDelegatorInterface
     */
    public function createElasticsearchSearchHandler()
    {
        if (count($this->getClientAdapterPlugins()) > 0) {
            return $this->createSearchDelegator();
        }

        // Supports only ElasticSearch before 7.0. For ElasticSearch 7+ use {@link \Spryker\Client\SearchElasticsearch\Plugin\ElasticsearchSearchAdapterPlugin}.
        return new ElasticsearchSearchHandler(
            $this->createIndexClientProvider(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected function createIndexClientProvider()
    {
        return new IndexClientProvider();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationFactoryInterface
     */
    public function createFacetAggregationFactory()
    {
        return new FacetAggregationFactory(
            $this->createPageIndexMap(),
            $this->createAggregationBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\FacetConfigBuilderInterface
     */
    public function createFacetConfigBuilder()
    {
        return new FacetConfigBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SortConfigBuilderInterface
     */
    public function createSortConfigBuilder()
    {
        return new SortConfigBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    public function createPaginationConfigBuilder()
    {
        return new PaginationConfigBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\QueryFactoryInterface
     */
    public function createQueryFactory()
    {
        return new QueryFactory($this->createQueryBuilder(), $this->getMoneyPlugin());
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function getMoneyPlugin()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\AggregationExtractorFactoryInterface
     */
    public function createAggregationExtractorFactory()
    {
        return new AggregationExtractorFactory();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Generated\Shared\Search\PageIndexMap
     */
    protected function createPageIndexMap()
    {
        return new PageIndexMap();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    public function createAggregationBuilder()
    {
        return new AggregationBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Suggest\SuggestBuilderInterface
     */
    public function createSuggestBuilder()
    {
        return new SuggestBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor\FacetValueTransformerFactoryInterface
     */
    public function createFacetValueTransformerFactory()
    {
        return new FacetValueTransformerFactory();
    }

    /**
     * @phpstan-return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchKeysQuery($searchString, $limit = null, $offset = null)
    {
        return new SearchKeysQuery($searchString, $limit, $offset);
    }

    /**
     * @phpstan-return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function createSearchStringQuery($searchString, $limit = null, $offset = null)
    {
        return new SearchStringQuery($searchString, $limit, $offset);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface>
     */
    public function getSearchConfigExpanderPlugins()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::SEARCH_CONFIG_EXPANDER_PLUGINS);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(SearchDependencyProvider::STORE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Writer\WriterInterface|\Spryker\Client\Search\Delegator\Adapter\SearchDelegatorAdapterInterface
     */
    public function createWriter()
    {
        if (count($this->getClientAdapterPlugins()) > 0) {
            return $this->createSearchDelegatorAdapter();
        }

        return new Writer(
            $this->createCachedElasticsearchClient(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Reader\ReaderInterface|\Spryker\Client\Search\Delegator\Adapter\SearchDelegatorAdapterInterface
     */
    public function createReader()
    {
        if (count($this->getClientAdapterPlugins()) > 0) {
            return $this->createSearchDelegatorAdapter();
        }

        return new Reader(
            $this->createCachedElasticsearchClient(),
            $this->getConfig()->getSearchIndexName(),
            $this->getConfig()->getSearchDocumentType(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Elastica\Client
     */
    public function createCachedElasticsearchClient()
    {
        if (static::$searchClient === null) {
            static::$searchClient = $this->getElasticsearchClient();
        }

        return static::$searchClient;
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientInterface
     */
    public function getLocaleClient(): SearchToLocaleClientInterface
    {
        return $this->getProvidedDependency(SearchDependencyProvider::CLIENT_LOCALE);
    }
}
