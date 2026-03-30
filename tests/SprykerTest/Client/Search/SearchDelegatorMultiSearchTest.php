<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Search\Delegator\SearchDelegator;
use Spryker\Client\Search\SearchContext\SearchContextExpanderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use SprykerTest\Client\Search\Plugin\Elasticsearch\Fixtures\MultiSearchAdapterPlugin;
use SprykerTest\Client\Search\Plugin\Elasticsearch\Fixtures\SearchContextAwareQueryPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group SearchDelegatorMultiSearchTest
 * Add your own group annotations below this line
 */
class SearchDelegatorMultiSearchTest extends Unit
{
    protected const string ADAPTER_NAME = 'test_adapter';

    protected SearchClientTester $tester;

    public function testMultiSearchCallsBatchMethodOnAdapterThatImplementsMultiSearchInterface(): void
    {
        // Arrange
        $searchContextTransfer = new SearchContextTransfer();

        $firstQuery = $this->createQueryPlugin($searchContextTransfer);

        $secondQuery = $this->createQueryPlugin($searchContextTransfer);

        $expectedResults = ['key_one' => ['result_a'], 'key_two' => ['result_b']];

        $adapter = $this->createMultiSearchCapableAdapter($expectedResults);

        $delegator = new SearchDelegator([$adapter], $this->createPassthroughContextExpander($searchContextTransfer));

        // Act
        $results = $delegator->multiSearch(
            ['key_one' => $firstQuery, 'key_two' => $secondQuery],
            ['key_one' => [], 'key_two' => []],
        );

        // Assert
        $this->assertSame($expectedResults, $results);
    }

    public function testMultiSearchFallsBackToSequentialSearchCallsForAdapterWithoutBatchSupport(): void
    {
        // Arrange
        $searchContextTransfer = new SearchContextTransfer();

        $firstQuery = $this->createQueryPlugin($searchContextTransfer);

        $secondQuery = $this->createQueryPlugin($searchContextTransfer);

        $adapter = $this->createSequentialOnlyAdapter();

        $delegator = new SearchDelegator([$adapter], $this->createPassthroughContextExpander($searchContextTransfer));

        // Act
        $results = $delegator->multiSearch(
            ['key_one' => $firstQuery, 'key_two' => $secondQuery],
            ['key_one' => [], 'key_two' => []],
        );

        // Assert
        $this->assertArrayHasKey('key_one', $results);
        $this->assertArrayHasKey('key_two', $results);
    }

    public function testMultiSearchResultKeysMatchInputQueryKeys(): void
    {
        // Arrange
        $searchContextTransfer = new SearchContextTransfer();

        $inputKeys = ['product_a', 'product_b', 'product_c'];
        $queries = [];

        foreach ($inputKeys as $key) {
            $queries[$key] = $this->createQueryPlugin($searchContextTransfer);
        }

        $adapterReturnValue = array_fill_keys($inputKeys, []);

        $adapter = $this->createMultiSearchCapableAdapter($adapterReturnValue);

        $delegator = new SearchDelegator([$adapter], $this->createPassthroughContextExpander($searchContextTransfer));

        // Act
        $results = $delegator->multiSearch($queries, array_fill_keys($inputKeys, []));

        // Assert
        $this->assertSame($inputKeys, array_keys($results));
    }

    public function testMultiSearchWithEmptyInputReturnsEmptyArray(): void
    {
        // Arrange
        $adapter = $this->createMultiSearchCapableAdapter([]);
        $delegator = new SearchDelegator([$adapter], $this->createPassthroughContextExpander(new SearchContextTransfer()));

        // Act
        $results = $delegator->multiSearch([], []);

        // Assert
        $this->assertSame([], $results);
    }

    protected function createQueryPlugin(SearchContextTransfer $searchContextTransfer): SearchContextAwareQueryPlugin
    {
        return new SearchContextAwareQueryPlugin($searchContextTransfer);
    }

    /**
     * @param array<string, mixed> $results
     */
    protected function createMultiSearchCapableAdapter(array $results): MultiSearchAdapterPlugin
    {
        return new MultiSearchAdapterPlugin($results);
    }

    protected function createSequentialOnlyAdapter(): SearchAdapterPluginInterface
    {
        $adapter = $this->createMock(SearchAdapterPluginInterface::class);
        $adapter->method('getName')->willReturn(static::ADAPTER_NAME);
        $adapter->method('isApplicable')->willReturn(true);
        $adapter->method('search')->willReturn([]);

        return $adapter;
    }

    protected function createPassthroughContextExpander(SearchContextTransfer $searchContextTransfer): SearchContextExpanderInterface
    {
        $expander = $this->createMock(SearchContextExpanderInterface::class);
        $expander->method('expandSearchContext')->willReturn($searchContextTransfer);

        return $expander;
    }
}
