<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Handler;

use Elastica\Exception\ResponseException;
use Elastica\ResultSet;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\Search\Exception\SearchResponseException;
use Spryker\Client\Search\Provider\IndexClientProvider;

/**
 * @deprecated Supports only ElasticSearch < 7.0 with one index and multi-mapping. Will be removed without replacement.
 */
class ElasticsearchSearchHandler implements SearchHandlerInterface
{
    /**
     * @var \Spryker\Client\Search\Provider\IndexClientProvider
     */
    protected $indexClientProvider;

    /**
     * @param \Spryker\Client\Search\Provider\IndexClientProvider $indexClientProvider
     */
    public function __construct(IndexClientProvider $indexClientProvider)
    {
        $this->indexClientProvider = $indexClientProvider;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<mixed> $requestParameters
     *
     * @return \Elastica\ResultSet|array<mixed>
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        $rawSearchResult = $this->executeQuery($searchQuery);

        if (!$resultFormatters) {
            return $rawSearchResult;
        }

        return $this->formatSearchResults($resultFormatters, $rawSearchResult, $requestParameters);
    }

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param \Elastica\ResultSet $rawSearchResult
     * @param array<mixed> $requestParameters
     *
     * @return array<mixed>
     */
    protected function formatSearchResults(array $resultFormatters, ResultSet $rawSearchResult, array $requestParameters)
    {
        $formattedSearchResult = [];

        foreach ($resultFormatters as $resultFormatter) {
            $formattedSearchResult[$resultFormatter->getName()] = $resultFormatter->formatResult($rawSearchResult, $requestParameters);
        }

        return $formattedSearchResult;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     *
     * @throws \Spryker\Client\Search\Exception\SearchResponseException
     *
     * @return \Elastica\ResultSet
     */
    protected function executeQuery(QueryInterface $query)
    {
        try {
            $query = $query->getSearchQuery();
            $client = $this->indexClientProvider->getClient();
            $rawSearchResult = $client->search($query);
        } catch (ResponseException $e) {
            $rawQuery = json_encode($query->toArray());

            throw new SearchResponseException(
                sprintf('Search failed with the following reason: %s. Query: %s', $e->getMessage(), $rawQuery),
                $e->getCode(),
                $e,
            );
        }

        return $rawSearchResult;
    }
}
