<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

interface SearchClientInterface
{
    /**
     * Specification:
     * - Connects to Elasticsearch client if possible
     * - Throws exception if connection fails
     *
     * @api
     *
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection();

    /**
     * Specification:
     * - Expands the base search query with multiple query expanders
     * - All expanders use the same search configuration provided by this client
     * - The expanders use the given parameters to adapt to the request
     * - Returns the expanded query
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $searchQueryExpanders
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = []);

    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client
     * - If there's no result formatter given then the raw search result will be returned
     * - The formatted search result will be an associative array where the keys are the name and the values are the formatted results
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<mixed> $requestParameters
     *
     * @return \Elastica\ResultSet|mixed|array (@deprecated Only mixed will be supported with the next major)
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);

    /**
     * Specification:
     * - Runs a simple full text search for the given search string
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|mixed|array (@deprecated Only mixed will be supported with the next major)
     */
    public function searchKeys($searchString, $limit = null, $offset = null);

    /**
     * Specification:
     * - Returns data from an external search service (e.g Elasticsearch)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return mixed
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer);

    /**
     * Specification:
     * - Writes data into an external search service (e.g Elasticsearch).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool;

    /**
     * Specification:
     * - Writes data into an external search service in bulk mode.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool;

    /**
     * Specification:
     * - Deletes data from an external search service (e.g Elasticsearch).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool;

    /**
     * Specification:
     * - Deletes data from an external search service (e.g Elasticsearch) in bulk mode.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool;

    /**
     * Specification:
     * - Returns a statically cached instance (for performance reasons) of the search configuration
     * - The result is the union of the hard-coded and the dynamic configurations
     * - Dynamic configuration is provided by \Spryker\Client\Search\SearchDependencyProvider::createSearchConfigExpanderPlugins()
     *
     * @api
     *
     * @deprecated Will be removed without replacement. This functionality is obsolete and should not be used.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig();

    /**
     * Specification:
     * - Runs a string search for the given search string
     * - @see https://www.elastic.co/guide/en/elasticsearch/reference/2.4/query-dsl-query-string-query.html
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Elastica\ResultSet|mixed|array (@deprecated Only mixed will be supported with the next major)
     */
    public function searchQueryString($searchString, $limit = null, $offset = null);

    /**
     * Specification:
     * - Returns data from an external search service (e.g Elasticsearch)
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClientInterface::readDocument()} instead.
     *
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read($key, $typeName = null, $indexName = null);

    /**
     * Specification:
     * - Writes data into an external search service (e.g Elasticsearch)
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClientInterface::writeDocument()} instead.
     *
     * @param array<string, mixed> $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = null, $indexName = null);

    /**
     * Specification:
     * - Writes data into an external search service in bulk mode.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClientInterface::writeDocuments()} instead.
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool;

    /**
     * Specification:
     * - Deletes data from an external search service (e.g Elasticsearch)
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClientInterface::deleteDocument()} instead.
     *
     * @param array<string, mixed> $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = null, $indexName = null);

    /**
     * Specification:
     * - Deletes data from an external search service (e.g Elasticsearch) in bulk mode.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClientInterface::deleteDocuments()} instead.
     *
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool;
}
