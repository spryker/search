<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\SuggestionByTypeQueryExpanderPlugin} instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SuggestionByTypeQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    public const AGGREGATION_NAME = 'suggestion-by-type';

    /**
     * @var string
     */
    public const NESTED_AGGREGATION_NAME = 'top-hits';

    /**
     * @var int
     */
    public const SIZE = 10;

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $query = $searchQuery->getSearchQuery();
        $this->addAggregation($query);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addAggregation(Query $query)
    {
        $topHits = $this->getFactory()
            ->createAggregationBuilder()
            ->createTopHitsAggregation(static::NESTED_AGGREGATION_NAME)
            ->setSize(static::SIZE);

        $termsAggregation = $this->getFactory()
            ->createAggregationBuilder()
            ->createTermsAggregation(static::AGGREGATION_NAME)
            ->setField(PageIndexMap::TYPE)
            ->addAggregation($topHits);

        $query->addAggregation($termsAggregation);
    }
}
