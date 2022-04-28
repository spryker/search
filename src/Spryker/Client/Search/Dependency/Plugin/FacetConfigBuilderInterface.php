<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Dependency\Plugin;

use Generated\Shared\Transfer\FacetConfigTransfer;

/**
 * @deprecated Use {@link \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface} instead.
 */
interface FacetConfigBuilderInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return $this
     */
    public function addFacet(FacetConfigTransfer $facetConfigTransfer);

    /**
     * @api
     *
     * @param string $facetName
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer|null
     */
    public function get($facetName);

    /**
     * @api
     *
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getAll();

    /**
     * @api
     *
     * @return array
     */
    public function getParamNames();

    /**
     * @api
     *
     * @param array<string, mixed> $requestParameters
     *
     * @return array<\Generated\Shared\Transfer\FacetConfigTransfer>
     */
    public function getActive(array $requestParameters);

    /**
     * @api
     *
     * @param array<string, mixed> $requestParameters
     *
     * @return array
     */
    public function getActiveParamNames(array $requestParameters);
}
