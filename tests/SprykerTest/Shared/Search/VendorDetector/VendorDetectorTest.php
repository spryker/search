<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Search\VendorDetector;

use Codeception\Test\Unit;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Shared\Search\VendorDetector\VendorDetector;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Search
 * @group VendorDetector
 * @group VendorDetectorTest
 * Add your own group annotations below this line
 */
class VendorDetectorTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Search\SearchTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->getStorageClient()->delete(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY);
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient(): StorageClientInterface
    {
        return new StorageClient();
    }

    /**
     * @return void
     */
    public function testIsElasticsearchReturnsTrueWhenNoConfigurationFound(): void
    {
        // Act
        $isElasticsearch = VendorDetector::isElasticsearch();

        // Assert
        $this->assertTrue($isElasticsearch);
    }

    /**
     * @return void
     */
    public function testIsElasticsearchReturnsTrueWhenConfigurationFoundAndSelectedAdapterIsElasticsearch(): void
    {
        // Arrange
        $storageClient = new StorageClient();
        $storageClient->set(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY, json_encode(['adapters' => ['Elasticsearch']]));

        // Act
        $isElasticsearch = VendorDetector::isElasticsearch();

        // Assert
        $this->assertTrue($isElasticsearch);
    }

    /**
     * @return void
     */
    public function testIsElasticsearchReturnsFalseWhenConfigurationFoundAndSelectedAdapterIsAlgolia(): void
    {
        // Arrange
        $storageClient = new StorageClient();
        $storageClient->set(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY, json_encode(['adapters' => ['Algolia']]));

        // Act
        $isElasticsearch = VendorDetector::isElasticsearch();

        // Assert
        $this->assertFalse($isElasticsearch);
    }

    /**
     * @return void
     */
    public function testIsElasticsearchReturnsTrueWhenStorageClientIsNotInitiated(): void
    {
        // Arrange
        $vendorDetectorStub = $this->tester->getVendorDetectorWithConnectionExceptionThrowingStorageClient();

        // Act
        $isElasticsearch = $vendorDetectorStub::isElasticsearch();

        // Assert
        $this->assertTrue($isElasticsearch);
    }

    /**
     * @return void
     */
    public function testIsElasticsearchReturnsFalseWhenVendorQueryParameterIsAlgolia(): void
    {
        // Arrange
        $_GET['v'] = 'algolia';

        // Act
        $isElasticsearch = VendorDetector::isElasticsearch();

        // Assert
        $this->assertFalse($isElasticsearch);
    }
}
