<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin;

use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Plugin\Config\SearchConfigInterface;

interface QueryExpanderPluginInterface
{

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Plugin\Config\SearchConfigInterface $searchConfig
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Model\Query\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, SearchConfigInterface $searchConfig, array $requestParameters = []);

}
