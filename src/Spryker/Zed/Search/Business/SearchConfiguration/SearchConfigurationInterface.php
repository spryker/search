<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\SearchConfiguration;

use Generated\Shared\Transfer\SearchConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\SearchConfigurationResponseTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;

interface SearchConfigurationInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationCriteriaTransfer $searchConfigurationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    public function getSearchConfiguration(SearchConfigurationCriteriaTransfer $searchConfigurationCriteriaTransfer): SearchConfigurationTransfer;

    /**
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationResponseTransfer
     */
    public function saveSearchConfiguration(SearchConfigurationTransfer $searchConfigurationTransfer): SearchConfigurationResponseTransfer;
}
