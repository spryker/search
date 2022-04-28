<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SuggestionByTypeQueryExpanderPlugin;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\SuggestionByTypeResultFormatterPlugin} instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SuggestionByTypeResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    /**
     * @var string
     */
    public const NAME = 'suggestionByType';

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $result = [];
        $aggregation = $searchResult->getAggregation(SuggestionByTypeQueryExpanderPlugin::AGGREGATION_NAME);

        foreach ($aggregation['buckets'] as $agg) {
            $type = $agg['key'];

            foreach ($agg[SuggestionByTypeQueryExpanderPlugin::NESTED_AGGREGATION_NAME]['hits']['hits'] as $hit) {
                $result[$type][] = $hit['_source'][PageIndexMap::SEARCH_RESULT_DATA];
            }
        }

        return $result;
    }
}
