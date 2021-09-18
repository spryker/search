<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SearchAdapterConfigurationTransfer;
use Generated\Shared\Transfer\SearchConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Facade
 * @group SearchFacadeTest
 * Add your own group annotations below this line
 */
class SearchFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const ELASTICSEARCH_ADAPTER = 'Elasticsearch';

    /**
     * @var string
     */
    protected const ALGOLIA_ADAPTER = 'Algolia';

    /**
     * @var string[]
     */
    protected const AVALIABLE_SEARCH_ADAPTERS = [
        'Elasticsearch',
        'Algolia',
    ];

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        putenv('TMPDIR=' . $this->tester->getVirtualDirectory());
    }

    /**
     * @return void
     */
    public function testSaveSearchConfigurationCreatesConfigurationIfConfigurationDoesNotExists()
    {
        // Arrange
        $searchConfigurationTransfer = (new SearchConfigurationTransfer())->setSelectedSearchAdapter(static::ALGOLIA_ADAPTER);

        // Act
        $searchConfigurationResponseTransfer = $this->tester->getFacade()->saveSearchConfiguration($searchConfigurationTransfer);

        // Assert
        $this->assertTrue($searchConfigurationResponseTransfer->getIsSuccesful());
    }

    /**
     * @return void
     */
    public function testSaveSearchConfigurationUpdatesConfigurationIfConfigurationExists()
    {
        // Arrange
        $elasticsearchConfigurationTransfer = (new SearchConfigurationTransfer())->setSelectedSearchAdapter(static::ELASTICSEARCH_ADAPTER);
        $algoliasearchConfigurationTransfer = (new SearchConfigurationTransfer())->setSelectedSearchAdapter(static::ALGOLIA_ADAPTER);
        $expectedSearchConfigurationTransfer = $this->prepareExpectedSearchConfigurationTransfer(static::ALGOLIA_ADAPTER);

        // Act
        $this->tester->getFacade()->saveSearchConfiguration($elasticsearchConfigurationTransfer);
        $this->tester->getFacade()->saveSearchConfiguration($algoliasearchConfigurationTransfer);
        $searchConfigurationTransfer = $this->tester->getFacade()->getSearchConfiguration(new SearchConfigurationCriteriaTransfer());

        // Assert
        $this->assertEquals($expectedSearchConfigurationTransfer, $searchConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testGetSearchConfigurationReturnsSearchConfigurationTransfer()
    {
        // Arrange
        $expectedSearchConfigurationTransfer = $this->prepareExpectedSearchConfigurationTransfer(static::ELASTICSEARCH_ADAPTER);

        // Act
        $searchConfigurationTransfer = $this->tester->getFacade()->getSearchConfiguration(new SearchConfigurationCriteriaTransfer());

        // Assert
        $this->assertEquals($expectedSearchConfigurationTransfer, $searchConfigurationTransfer);
    }

    /**
     * @param string $expectedAdapterName
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    protected function prepareExpectedSearchConfigurationTransfer(string $expectedAdapterName): SearchConfigurationTransfer
    {
        $searchConfigurationTransfer = (new SearchConfigurationTransfer());

        foreach (static::AVALIABLE_SEARCH_ADAPTERS as $adapterName) {
            $searchAdapterConfigurationTransfer = (new SearchAdapterConfigurationTransfer())
                ->setName($adapterName)
                ->setIsEnabled($adapterName === $expectedAdapterName);

            $searchConfigurationTransfer->addSearchAdapterConfiguration($searchAdapterConfigurationTransfer);
        }

        return $searchConfigurationTransfer;
    }
}
