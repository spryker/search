<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Mapper;

use Generated\Shared\Transfer\SearchConfigurationResponseTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;

interface SearchConfigurationMapperInterface
{
    /**
     * @param string[] $selectedSearchAdapters
     * @param string[] $avaliableSearchAdapters
     * @param \Generated\Shared\Transfer\SearchConfigurationTransfer $searchConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationTransfer
     */
    public function mapSearchConfigurationDataToSearchConfigurationTransfer(
        array $selectedSearchAdapters,
        array $avaliableSearchAdapters,
        SearchConfigurationTransfer $searchConfigurationTransfer
    ): SearchConfigurationTransfer;

    /**
     * @param bool $saveResult
     * @param \Generated\Shared\Transfer\SearchConfigurationResponseTransfer $searchConfigurationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationResponseTransfer
     */
    public function mapSaveSearchConfigurationResultToSearchConfigurationResponseTransfer(
        bool $saveResult,
        SearchConfigurationResponseTransfer $searchConfigurationResponseTransfer
    ): SearchConfigurationResponseTransfer;
}
