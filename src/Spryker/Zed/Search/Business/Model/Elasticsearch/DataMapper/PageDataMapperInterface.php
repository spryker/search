<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @deprecated Will be removed without replacement.
 */
interface PageDataMapperInterface
{
    /**
     * @deprecated Use {@link transferDataByMapperName()} instead.
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapData(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer);

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @throws \Spryker\Zed\Search\Business\Exception\PluginNotFoundException
     *
     * @return array
     */
    public function transferDataByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName);
}
