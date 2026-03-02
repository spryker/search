<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Search\HealthCheck;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;

interface HealthCheckInterface
{
    public function executeHealthCheck(): HealthCheckServiceResponseTransfer;
}
