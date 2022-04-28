<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\FacetQueryExpanderPlugin;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\FacetResultFormatterPlugin} instead.
 *
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class FacetResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    /**
     * @var string
     */
    public const NAME = 'facets';

    /**
     * @var string
     */
    public const PATH_SEPARATOR = '.';

    /**
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $facetData = [];

        $facetConfig = $this
            ->getFactory()
            ->getSearchConfig()
            ->getFacetConfigBuilder();

        $aggregations = $searchResult->getAggregations();

        foreach ($facetConfig->getAll() as $facetName => $facetConfigTransfer) {
            $extractor = $this
                ->getFactory()
                ->createAggregationExtractorFactory()
                ->create($facetConfigTransfer);

            $aggregation = $this->getAggregationRawData($aggregations, $facetConfigTransfer);

            if ($aggregation) {
                $facetData[$facetName] = $extractor->extractDataFromAggregations($aggregation, $requestParameters);
            }
        }

        return $facetData;
    }

    /**
     * @param array<string, mixed> $aggregations
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return array
     */
    protected function getAggregationRawData(array $aggregations, FacetConfigTransfer $facetConfigTransfer)
    {
        $fieldName = $this->getFieldName($facetConfigTransfer);
        $bucketName = $this->getBucketName($facetConfigTransfer);

        if (isset($aggregations[$bucketName])) {
            return $aggregations[$bucketName][FacetQueryExpanderPlugin::AGGREGATION_FILTER_NAME][$fieldName];
        }

        if (isset($aggregations[$fieldName])) {
            return $aggregations[$fieldName];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getBucketName(FacetConfigTransfer $facetConfigTransfer)
    {
        return FacetQueryExpanderPlugin::AGGREGATION_GLOBAL_PREFIX . $facetConfigTransfer->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getFieldName(FacetConfigTransfer $facetConfigTransfer)
    {
        if ($facetConfigTransfer->getAggregationParams()) {
            return $this->addNestedFieldPrefix(
                $facetConfigTransfer->getFieldName(),
                $facetConfigTransfer->getName(),
            );
        }

        return $facetConfigTransfer->getFieldName();
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
