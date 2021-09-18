<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\SearchConfiguration;

use Generated\Shared\Transfer\SearchConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\SearchConfigurationResponseTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;
use Spryker\Client\Storage\StorageClient;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Search\Business\Mapper\SearchConfigurationMapperInterface;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;
use Spryker\Zed\Search\SearchConfig;

class SearchConfiguration implements SearchConfigurationInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_SEARCH_ADAPTER = 'Elasticsearch';

    /**
     * @var string
     */
    protected const FIELD_ADAPTERS = 'adapters';

    /**
     * @var string[]
     */
    protected const AVALIABLE_SEARCH_ADAPTERS = [
        'Elasticsearch',
        'Algolia',
    ];

    /**
     * @var \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Search\SearchConfig
     */
    protected $searchConfig;

    /**
     * @var \Spryker\Zed\Search\Business\Mapper\SearchConfigurationMapperInterface
     */
    protected $searchConfigurationMapper;

    /**
     * @param \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Search\SearchConfig $searchConfig
     * @param \Spryker\Zed\Search\Business\Mapper\SearchConfigurationMapperInterface $searchConfigurationMapper
     */
    public function __construct(
        SearchToUtilEncodingInterface $utilEncodingService,
        SearchConfig $searchConfig,
        SearchConfigurationMapperInterface $searchConfigurationMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->searchConfig = $searchConfig;
        $this->searchConfigurationMapper = $searchConfigurationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationCriteriaTransfer $searchConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    public function getSearchConfiguration(SearchConfigurationCriteriaTransfer $searchConfigurationCriteriaTransfer): SearchConfigurationTransfer
    {
        $storageClient = new StorageClient();
        $searchConfiguration = $storageClient->get(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY);

        $searchConfigurationTransfer = new SearchConfigurationTransfer();

        if (!$searchConfiguration) {
            return $this->searchConfigurationMapper->mapSearchConfigurationDataToSearchConfigurationTransfer(
                [static::DEFAULT_SEARCH_ADAPTER],
                static::AVALIABLE_SEARCH_ADAPTERS,
                $searchConfigurationTransfer
            );
        }

        $selectedSearchAdapters = $searchConfiguration[static::FIELD_ADAPTERS] ?? [static::DEFAULT_SEARCH_ADAPTER];

        return $this->searchConfigurationMapper->mapSearchConfigurationDataToSearchConfigurationTransfer(
            $selectedSearchAdapters,
            static::AVALIABLE_SEARCH_ADAPTERS,
            $searchConfigurationTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationResponseTransfer
     */
    public function saveSearchConfiguration(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationResponseTransfer
    {
        if (!$searchConfigurationTransfer->getSelectedSearchAdapter()) {
            return $this->searchConfigurationMapper->mapSaveSearchConfigurationResultToSearchConfigurationResponseTransfer(
                false,
                new SearchConfigurationResponseTransfer()
            );
        }

        $searchConfigurationData = [];
        $searchConfigurationData[static::FIELD_ADAPTERS][] = $searchConfigurationTransfer->getSelectedSearchAdapter();

        $storageClient = new StorageClient();
        $storageClient->set(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY, $this->utilEncodingService->encodeJson($searchConfigurationData));

        return $this->searchConfigurationMapper->mapSaveSearchConfigurationResultToSearchConfigurationResponseTransfer(
            true,
            new SearchConfigurationResponseTransfer()
        );
    }
}
