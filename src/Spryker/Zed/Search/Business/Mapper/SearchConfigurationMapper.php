<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\SearchAdapterConfigurationTransfer;
use Generated\Shared\Transfer\SearchConfigurationResponseTransfer;
use Generated\Shared\Transfer\SearchConfigurationTransfer;

class SearchConfigurationMapper implements SearchConfigurationMapperInterface
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
    ): SearchConfigurationTransfer {
        foreach ($avaliableSearchAdapters as $adapterName) {
            $searchAdapterConfigurationTransfer = (new SearchAdapterConfigurationTransfer())
                ->setName($adapterName)
                ->setIsEnabled(in_array($adapterName, $selectedSearchAdapters));

            $searchConfigurationTransfer->addSearchAdapterConfiguration($searchAdapterConfigurationTransfer);
        }

        return $searchConfigurationTransfer;
    }

    /**
     * @param bool $saveResult
     * @param \Generated\Shared\Transfer\SearchConfigurationResponseTransfer $searchConfigurationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SearchConfigurationResponseTransfer
     */
    public function mapSaveSearchConfigurationResultToSearchConfigurationResponseTransfer(
        bool $saveResult,
        SearchConfigurationResponseTransfer $searchConfigurationResponseTransfer
    ): SearchConfigurationResponseTransfer {
        $isSuccesful = $saveResult !== false ? true : false;
        $messageTransfer = new MessageTransfer();

        if (!$isSuccesful) {
            $messageTransfer->setMessage('Please try again later, not possible to save');
        }

        return $searchConfigurationResponseTransfer
            ->setIsSuccesful($isSuccesful)
            ->setMessages(new ArrayObject([$messageTransfer]));
    }
}
