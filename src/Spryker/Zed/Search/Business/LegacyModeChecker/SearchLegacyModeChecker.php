<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\LegacyModeChecker;

/**
 * @deprecated Will be removed without replacement.
 */
class SearchLegacyModeChecker implements SearchLegacyModeCheckerInterface
{
    /**
     * @var array<\Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface>
     */
    protected $sourceInstallerPlugins;

    /**
     * @param array<\Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface> $sourceInstallerPlugins
     */
    public function __construct(array $sourceInstallerPlugins)
    {
        $this->sourceInstallerPlugins = $sourceInstallerPlugins;
    }

    public function isInLegacyMode(): bool
    {
        return !count($this->sourceInstallerPlugins);
    }
}
