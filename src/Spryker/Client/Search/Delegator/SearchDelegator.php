<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Exception;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Search\Exception\SearchDelegatorException;
use Spryker\Client\Search\SearchContext\SearchContextExpanderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\MultiSearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class SearchDelegator implements SearchDelegatorInterface
{
    protected const string KEY_QUERIES = 'queries';

    protected const string KEY_FORMATTERS = 'formatters';

    /**
     * @var array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface>
     */
    protected $searchAdapterPlugins;

    /**
     * @var \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface
     */
    protected $searchContextExpander;

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface> $searchAdapterPlugins
     * @param \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface $searchContextExpander
     */
    public function __construct(
        array $searchAdapterPlugins,
        SearchContextExpanderInterface $searchContextExpander
    ) {
        $this->searchAdapterPlugins = $this->getSearchAdapterPluginsIndexedByName($searchAdapterPlugins);
        $this->searchContextExpander = $searchContextExpander;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $query
     * @param array $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $query, array $resultFormatters = [], array $requestParameters = [])
    {
        $searchContextTransfer = $this->getSearchContext($query);
        $searchContextTransfer = $this->expandSearchContext($searchContextTransfer);
        $query = $this->setSearchContext($query, $searchContextTransfer);

        return $this->getSearchAdapter($searchContextTransfer)
            ->search($query, $resultFormatters, $requestParameters);
    }

    /**
     * @deprecated Will be replaced with inline usage when SearchContextAwareQueryInterface is merged into QueryInterface.
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface|\Spryker\Client\CatalogExtension\Dependency\Plugin\SearchTypeIdentifierInterface $searchQuery
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function getSearchContext($searchQuery): SearchContextTransfer
    {
        if (!$searchQuery instanceof SearchContextAwareQueryInterface) {
            throw new Exception(sprintf('Your query class "%s" must implement %s interface.', get_class($searchQuery), SearchContextAwareQueryInterface::class));
        }

        return $searchQuery->getSearchContext();
    }

    /**
     * @deprecated Will be replaced with inline usage when SearchContextAwareQueryInterface is merged into QueryInterface.
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface|\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function setSearchContext($searchQuery, SearchContextTransfer $searchContextTransfer)
    {
        $searchQuery->setSearchContext($searchContextTransfer);

        return $searchQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return mixed
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer)
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);

        return $this->getSearchAdapter($searchDocumentTransfer->getSearchContext())->readDocument($searchDocumentTransfer);
    }

    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        $plugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());

        return $plugin->writeDocument($searchDocumentTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        $overallResult = true;
        $searchDocumentTransfers = $this->expandSearchContextTransferForSearchDocumentTransfers($searchDocumentTransfers);
        $searchDocumentTransfersBySearchAdapterPluginName = $this->groupSearchDocumentTransfersBySearchAdapterPluginName($searchDocumentTransfers);

        foreach ($searchDocumentTransfersBySearchAdapterPluginName as $searchAdapterPluginName => $searchDocumentTransfers) {
            $singleOperationResult = $this->searchAdapterPlugins[$searchAdapterPluginName]->writeDocuments($searchDocumentTransfers);

            if (!$singleOperationResult) {
                $overallResult = false;
            }
        }

        return $overallResult;
    }

    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        $plugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());

        return $plugin->deleteDocument($searchDocumentTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        $overallResult = true;
        $searchDocumentTransfers = $this->expandSearchContextTransferForSearchDocumentTransfers($searchDocumentTransfers);
        $searchDocumentTransfersBySearchAdapterPluginName = $this->groupSearchDocumentTransfersBySearchAdapterPluginName($searchDocumentTransfers);

        foreach ($searchDocumentTransfersBySearchAdapterPluginName as $searchAdapterPluginName => $searchDocumentTransfersPerAdapter) {
            $singleOperationResult = $this->searchAdapterPlugins[$searchAdapterPluginName]->deleteDocuments($searchDocumentTransfersPerAdapter);

            if (!$singleOperationResult) {
                $overallResult = false;
            }
        }

        return $overallResult;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\SearchDocumentTransfer>>
     */
    protected function groupSearchDocumentTransfersBySearchAdapterPluginName(array $searchDocumentTransfers): array
    {
        $searchContextTransfersPerAdapter = [];

        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $searchAdapterPlugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());
            $searchContextTransfersPerAdapter[$searchAdapterPlugin->getName()][] = $searchDocumentTransfer;
        }

        return $searchContextTransfersPerAdapter;
    }

    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface> $searchAdapterPlugins
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface>
     */
    protected function getSearchAdapterPluginsIndexedByName(array $searchAdapterPlugins): array
    {
        $searchAdapterPluginsIndexedByVendorName = [];

        foreach ($searchAdapterPlugins as $searchAdapterPlugin) {
            $searchAdapterPluginsIndexedByVendorName[$searchAdapterPlugin->getName()] = $searchAdapterPlugin;
        }

        return $searchAdapterPluginsIndexedByVendorName;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Spryker\Client\Search\Exception\SearchDelegatorException
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface
     */
    protected function getSearchAdapter(SearchContextTransfer $searchContextTransfer): SearchAdapterPluginInterface
    {
        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            if ($searchAdapterPlugin->isApplicable($searchContextTransfer)) {
                return $searchAdapterPlugin;
            }
        }

        throw new SearchDelegatorException(sprintf(
            'None of the applied "%s"s is applicable for the specified context.',
            SearchAdapterPluginInterface::class,
        ));
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return array<\Generated\Shared\Transfer\SearchDocumentTransfer>
     */
    protected function expandSearchContextTransferForSearchDocumentTransfers(array $searchDocumentTransfers): array
    {
        return array_map(function (SearchDocumentTransfer $searchDocumentTransfer) {
            return $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        }, $searchDocumentTransfers);
    }

    protected function expandSearchContextTransferForSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        $mappedSearchContextTransfer = $this->expandSearchContext($searchDocumentTransfer->getSearchContext());
        $searchDocumentTransfer->setSearchContext($mappedSearchContextTransfer);

        return $searchDocumentTransfer;
    }

    protected function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        return $this->searchContextExpander->expandSearchContext($searchContextTransfer);
    }

    /**
     * @param array<string, \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface> $searchQueries
     * @param array<string, array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>> $resultFormattersPerQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return array<string, mixed>
     */
    public function multiSearch(array $searchQueries, array $resultFormattersPerQuery, array $requestParameters = []): array
    {
        $queriesGroupedByAdapter = $this->groupQueriesByAdapter($searchQueries, $resultFormattersPerQuery);
        $results = [];

        foreach ($queriesGroupedByAdapter as $adapterName => $adapterGroup) {
            $adapter = $this->searchAdapterPlugins[$adapterName];

            if ($adapter instanceof MultiSearchAdapterPluginInterface) {
                $adapterResults = $adapter->multiSearch(
                    $adapterGroup[static::KEY_QUERIES],
                    $adapterGroup[static::KEY_FORMATTERS],
                    $requestParameters,
                );
                // Keys are unique per query and each key is routed to exactly one adapter by groupQueriesByAdapter
                $results += $adapterResults;

                continue;
            }

            foreach ($adapterGroup[static::KEY_QUERIES] as $key => $query) {
                $results[$key] = $adapter->search($query, $adapterGroup[static::KEY_FORMATTERS][$key] ?? [], $requestParameters);
            }
        }

        return $results;
    }

    /**
     * @param array<string, \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface> $searchQueries
     * @param array<string, array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface>> $resultFormattersPerQuery
     *
     * @return array<string, array<string, mixed>>
     */
    protected function groupQueriesByAdapter(array $searchQueries, array $resultFormattersPerQuery): array
    {
        $queriesGroupedByAdapter = [];

        foreach ($searchQueries as $key => $query) {
            $searchContextTransfer = $this->getSearchContext($query);
            $searchContextTransfer = $this->expandSearchContext($searchContextTransfer);
            $query = $this->setSearchContext($query, $searchContextTransfer);
            $adapter = $this->getSearchAdapter($searchContextTransfer);
            $adapterName = $adapter->getName();

            $queriesGroupedByAdapter[$adapterName][static::KEY_QUERIES][$key] = $query;
            $queriesGroupedByAdapter[$adapterName][static::KEY_FORMATTERS][$key] = $resultFormattersPerQuery[$key] ?? [];
        }

        return $queriesGroupedByAdapter;
    }
}
