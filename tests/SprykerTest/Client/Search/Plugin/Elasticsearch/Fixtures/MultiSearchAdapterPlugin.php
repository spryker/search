<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\Fixtures;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\MultiSearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;

class MultiSearchAdapterPlugin implements SearchAdapterPluginInterface, MultiSearchAdapterPluginInterface
{
    /**
     * @param array<string, mixed> $multiSearchResults
     */
    public function __construct(protected array $multiSearchResults = [])
    {
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, mixed>
     */
    public function multiSearch(array $searchQueries, array $resultFormattersPerQuery, array $requestParameters = []): array
    {
        return $this->multiSearchResults;
    }

    /**
     * {@inheritDoc}
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return [];
    }

    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return $searchDocumentTransfer;
    }

    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return true;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return true;
    }

    public function isApplicable(SearchContextTransfer $searchContextTransfer): bool
    {
        return true;
    }

    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return true;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'test_adapter';
    }
}
