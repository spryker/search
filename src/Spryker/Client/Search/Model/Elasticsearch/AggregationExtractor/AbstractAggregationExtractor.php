<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\AggregationExtractor\AbstractAggregationExtractor} instead.
 */
abstract class AbstractAggregationExtractor implements AggregationExtractorInterface
{
    /**
     * @var string
     */
    public const PATH_SEPARATOR = '.';

    /**
     * @var string
     */
    public const DOC_COUNT = 'doc_count';

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getNestedFieldName(FacetConfigTransfer $facetConfigTransfer)
    {
        $nestedFieldName = $facetConfigTransfer->getFieldName();

        if ($facetConfigTransfer->getAggregationParams()) {
            $nestedFieldName = $this->addNestedFieldPrefix(
                $nestedFieldName,
                $facetConfigTransfer->getName(),
            );
        }

        return $nestedFieldName;
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix($fieldName, $nestedFieldName)
    {
        return $fieldName . static::PATH_SEPARATOR . $nestedFieldName;
    }
}
