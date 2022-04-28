<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use ArrayObject;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\AggregationExtractor\CategoryExtractor} instead.
 */
class CategoryExtractor implements AggregationExtractorInterface
{
    /**
     * @var string
     */
    public const DOC_COUNT = 'doc_count';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        $name = $this->facetConfigTransfer->getName();

        $facetResultValueTransfers = $this->extractFacetData($aggregations);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($name)
            ->setValues($facetResultValueTransfers)
            ->setConfig(clone $this->facetConfigTransfer);

        if (isset($requestParameters[$name])) {
            $facetResultTransfer->setActiveValue($requestParameters[$name]);
        }

        return $facetResultTransfer;
    }

    /**
     * @param array<string, mixed> $aggregation
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FacetSearchResultValueTransfer>
     */
    protected function extractFacetData(array $aggregation)
    {
        $facetValues = new ArrayObject();
        foreach ($aggregation['buckets'] as $bucket) {
            $facetResultValueTransfer = new FacetSearchResultValueTransfer();
            $facetResultValueTransfer
                ->setValue($bucket['key'])
                ->setDocCount($bucket[static::DOC_COUNT]);

            $facetValues->append($facetResultValueTransfer);
        }

        return $facetValues;
    }
}
