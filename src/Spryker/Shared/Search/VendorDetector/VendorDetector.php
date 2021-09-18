<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search\VendorDetector;

use Spryker\Client\Storage\StorageClient;
use Spryker\Shared\Search\SearchConfig;
use Spryker\Shared\Search\SearchConstants;
use Symfony\Component\HttpFoundation\Request;

class VendorDetector
{
    /**
     * @return bool
     */
    public static function isElasticsearch(): bool
    {
        $request = Request::createFromGlobals();

        if ($request->query->has('v') && $request->query->get('v') === 'algolia') {
            return false;
        }

        $storageClient = new StorageClient();
        $searchConfiguration = $storageClient->get(SearchConstants::SEARCH_CONFIGURATION_STORAGE_KEY);

        if (!$searchConfiguration) {
            return true;
        }

        $enabledSearchAdapters = $searchConfiguration['adapters'] ?? [SearchConfig::ELASTICSEARCH_ADAPTER];

        return in_array(SearchConfig::ELASTICSEARCH_ADAPTER, $enabledSearchAdapters);
    }
}
