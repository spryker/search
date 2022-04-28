<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\AggregationExtractor;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\AggregationExtractor\PriceRangeExtractor} instead.
 */
class PriceRangeExtractor extends RangeExtractor
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, MoneyPluginInterface $moneyPlugin)
    {
        parent::__construct($facetConfigTransfer);

        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters)
    {
        /** @var \Generated\Shared\Transfer\RangeSearchResultTransfer $rangeResultTransfer */
        $rangeResultTransfer = parent::extractDataFromAggregations($aggregations, $requestParameters);

        return $rangeResultTransfer;
    }

    /**
     * @param array<string, mixed> $requestParameters
     * @param float $min
     * @param float $max
     *
     * @return array
     */
    protected function getActiveRangeData(array $requestParameters, $min, $max)
    {
        [$activeMin, $activeMax] = $this->getActiveRangeParameters($requestParameters);

        return [
            $activeMin !== null ? $this->moneyPlugin->convertDecimalToInteger($activeMin) : $min,
            $activeMax !== null ? $this->moneyPlugin->convertDecimalToInteger($activeMax) : $max,
        ];
    }

    /**
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    protected function getActiveRangeParameters(array $requestParameters): array
    {
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $activeMin = null;
        if (!empty($requestParameters[$parameterName]['min'])) {
            $activeMin = (float)$requestParameters[$parameterName]['min'];
        }

        $activeMax = null;
        if (!empty($requestParameters[$parameterName]['max'])) {
            $activeMax = (float)$requestParameters[$parameterName]['max'];
        }

        return [$activeMin, $activeMax];
    }
}
