<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SearchConfig extends AbstractSharedConfig
{
    /**
     * @api
     *
     * Available facet types
     *
     * @var string
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';

    /**
     * @api
     *
     * @var string
     */
    public const FACET_TYPE_RANGE = 'range';

    /**
     * @api
     *
     * @var string
     */
    public const FACET_TYPE_PRICE_RANGE = 'price-range';

    /**
     * @api
     *
     * @var string
     */
    public const FACET_TYPE_CATEGORY = 'category';
}
