<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Search\Plugin\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;

/**
 * @method \Spryker\Glue\Search\SearchFactory getFactory()
 */
class SearchHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    /**
     * @var string
     */
    protected const SEARCH_HEALTH_CHECK_SERVICE_NAME = 'search';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::SEARCH_HEALTH_CHECK_SERVICE_NAME;
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
        return $this->getFactory()->createSearchHealthChecker()->executeHealthCheck();
    }
}
