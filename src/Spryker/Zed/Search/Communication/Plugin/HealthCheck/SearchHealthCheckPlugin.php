<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Shared\Search\SearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\Search\Business\SearchFacade getFacade()
 * @method \Spryker\Zed\Search\SearchConfig getConfig()
 */
class SearchHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return SearchConfig::SEARCH_SERVICE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function check(): HealthCheckServiceResponseTransfer
    {
        return $this->getFacade()->executeSearchHealthCheck();
    }
}
