<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\VendorDetector;

use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Search\SearchConfig;
use Spryker\Shared\Search\SearchConstants;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class VendorDetector
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface|null
     */
    protected static $storageClient;

    /**
     * @return bool
     */
    public static function isElasticsearch(): bool
    {
        $request = Request::createFromGlobals();

        if ($request->query->has('v') && $request->query->get('v') === 'algolia') {
            return false;
        }

        try {
            $storageClient = static::getStorageClient();
            $searchConfiguration = $storageClient->get(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY);
        } catch (Throwable $e) {
            return true;
        }

        if (!$searchConfiguration) {
            return true;
        }

        $enabledSearchAdapters = $searchConfiguration['adapters'] ?? [SearchConfig::ELASTICSEARCH_ADAPTER];

        return in_array(SearchConfig::ELASTICSEARCH_ADAPTER, $enabledSearchAdapters);
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected static function getStorageClient(): StorageClientInterface
    {
        if (static::$storageClient === null) {
            static::$storageClient = new StorageClient();
        }

        return static::$storageClient;
    }
}
