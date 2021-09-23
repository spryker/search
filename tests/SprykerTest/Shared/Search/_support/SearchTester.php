<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Search;

use Codeception\Actor;
use Codeception\Stub;
use Predis\Connection\ConnectionException;
use Spryker\Client\Storage\StorageClient;
use Spryker\Shared\Search\VendorDetector\VendorDetector;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class SearchTester extends Actor
{
    use _generated\SearchTesterActions;

    /**
     * @return \Spryker\Shared\Search\VendorDetector\VendorDetector
     */
    public function getVendorDetectorWithConnectionExceptionThrowingStorageClient(): VendorDetector
    {
        $storageClientStub = Stub::make(StorageClient::class, [
            'get' => function () {
                throw new ConnectionException('Storage client not initialized');
            },
        ]);

        $vendorDetectorStub = Stub::make(VendorDetector::class, [
            'getStorageClient' => $storageClientStub,
        ]);

        return $vendorDetectorStub;
    }
}
