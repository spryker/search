<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Search\Model\Query\QueryInterface;
use Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface;

interface SearchClientInterface
{

    /**
     * @api
     *
     * @return \Elastica\Index
     *
     * @deprecated This method will be removed.
     */
    public function getIndexClient();

    /**
     * @api
     *
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Search\Model\ResultFormatter\ResultFormatterInterface $resultFormatter
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, ResultFormatterInterface $resultFormatter);

}
