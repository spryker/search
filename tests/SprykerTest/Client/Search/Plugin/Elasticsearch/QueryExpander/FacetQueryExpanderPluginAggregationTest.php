<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group FacetQueryExpanderPluginAggregationTest
 * Add your own group annotations below this line
 */
class FacetQueryExpanderPluginAggregationTest extends AbstractFacetQueryExpanderPluginAggregationTest
{
    public function facetQueryExpanderDataProvider(): array
    {
        return [
            'single string facet' => $this->createStringFacetData(),
            'multiple string facets' => $this->createMultiStringFacetData(),
            'single integer facet' => $this->createIntegerFacetData(),
            'multiple integer facets' => $this->createMultiIntegerFacetData(),
            'single category facet' => $this->createCategoryFacetData(),
            'multiple category facets' => $this->createMultiCategoryFacetData(),
            'mixed facets' => $this->createMixedFacetData(),
        ];
    }

    protected function createStringFacetData(): array
    {
        $searchConfig = $this->createStringSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createMultiStringFacetData(): array
    {
        $searchConfig = $this->createMultiStringSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedStringFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createIntegerFacetData(): array
    {
        $searchConfig = $this->createIntegerSearchConfig();

        $expectedAggregations = [
            $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createMultiIntegerFacetData(): array
    {
        $searchConfig = $this->createMultiIntegerSearchConfig();

        $expectedAggregations = [
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createCategoryFacetData(): array
    {
        $searchConfig = $this->createCategorySearchConfig();

        $expectedAggregations = [
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createMultiCategoryFacetData(): array
    {
        $searchConfig = $this->createMultiCategorySearchConfig();

        $expectedAggregations = [
            $this->getExpectedCategoryFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }

    protected function createMixedFacetData(): array
    {
        $searchConfig = $this->createMixedSearchConfig();

        $expectedAggregations = [
            $this->getExpectedStringFacetAggregation(),
            $this->getExpectedIntegerFacetAggregation(),
            $this->getExpectedCategoryFacetAggregation(),
        ];

        return [$searchConfig, $expectedAggregations];
    }
}
