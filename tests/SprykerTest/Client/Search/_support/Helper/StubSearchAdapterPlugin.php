<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Client\Search\Helper;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use RuntimeException;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;

/**
 * Answers every search with the result the test stubbed, so search-backed resources can be driven on
 * the docker-free host lane without an Elasticsearch or Algolia connection. A search before anything
 * was stubbed is an arrangement bug and throws.
 *
 * Applicable for every context on purpose: the delegator takes the first applicable adapter, and
 * this plugin only ever reaches a suite that replaced the whole adapter list with it.
 *
 * Write operations are accepted and discarded — nothing reads them back, and refusing them would
 * make an unrelated publish step fail for a reason that has nothing to do with the test.
 */
class StubSearchAdapterPlugin implements SearchAdapterPluginInterface
{
    protected const string PLUGIN_NAME = 'stub-search-adapter';

    /**
     * @var array<string, mixed>|null
     */
    protected ?array $cannedResult = null;

    /**
     * @param array<string, mixed> $cannedResult Keys are result-formatter names ('products', 'pagination', ...).
     */
    public function setCannedResult(array $cannedResult): void
    {
        $this->cannedResult = $cannedResult;
    }

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        // Returning nothing would read as "found no documents" and let an assertion pass on a result
        // the test never arranged.
        if ($this->cannedResult === null) {
            throw new RuntimeException(sprintf(
                'A search ran before any result was stubbed. Call %s::stubSearchResult() in the Arrange '
                . 'step — with an empty array when the test is about an empty result set.',
                SearchResponseStubHelper::class,
            ));
        }

        // Formatters normally run inside the adapter, so the canned value is the post-formatting array.
        return $this->cannedResult;
    }

    public function isApplicable(SearchContextTransfer $searchContextTransfer): bool
    {
        return true;
    }

    public function getName(): string
    {
        return static::PLUGIN_NAME;
    }

    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        return $searchDocumentTransfer;
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
}
