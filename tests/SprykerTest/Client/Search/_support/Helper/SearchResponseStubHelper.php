<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Client\Search\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Client\Search\SearchDependencyProvider;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

/**
 * Lets a test decide what a search returns, so search-backed resources can be exercised on the
 * docker-free host lane. The canned value is the post-formatting result: the Catalog client, the
 * query plugins and the query expanders still run, but the query they build is discarded instead of
 * executed, and the result formatter plugins are bypassed entirely. Only the resource mappers above
 * the Search client run for real against the canned value.
 *
 * The Search client is resolved through the Spryker locator rather than Symfony DI, so the seam is
 * the adapter plugin list and not a `setService()` binding.
 *
 * Enable it alongside {@see \SprykerTest\Shared\Testify\Helper\DependencyHelper}, which carries the
 * binding.
 */
class SearchResponseStubHelper extends Module
{
    use DependencyHelperTrait;

    protected StubSearchAdapterPlugin $stubSearchAdapterPlugin;

    public function _before(TestInterface $test): void
    {
        $this->stubSearchAdapterPlugin = new StubSearchAdapterPlugin();

        // Replaces the whole adapter list: the delegator takes the first applicable plugin, and the
        // real Elasticsearch adapter is applicable for 'page' sources — appending would not stub it out.
        $this->getDependencyHelper()->setDependency(
            SearchDependencyProvider::PLUGINS_CLIENT_ADAPTER,
            [$this->stubSearchAdapterPlugin],
        );
    }

    /**
     * @param array<string, mixed> $formattedSearchResult Keys are result-formatter names ('products', 'pagination', ...).
     */
    public function stubSearchResult(array $formattedSearchResult): void
    {
        $this->stubSearchAdapterPlugin->setCannedResult($formattedSearchResult);
    }
}
