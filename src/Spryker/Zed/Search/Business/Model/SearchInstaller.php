<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model;

use Psr\Log\LoggerInterface;

class SearchInstaller implements SearchInstallerInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @deprecated Use {@link \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface} instead.
     *
     * @var array<\Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface|\Spryker\Zed\Search\Business\Model\SearchInstallerInterface>
     */
    protected $installer = [];

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param array<\Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface|\Spryker\Zed\Search\Business\Model\SearchInstallerInterface> $installer Deprecated: Use {@link \Spryker\Zed\SearchExtension\Dependency\Plugin\InstallPluginInterface} instead.
     */
    public function __construct(LoggerInterface $logger, array $installer)
    {
        $this->logger = $logger;
        $this->installer = $installer;
    }

    /**
     * @return void
     */
    public function install()
    {
        foreach ($this->installer as $installer) {
            if ($installer instanceof SearchInstallerInterface) {
                $installer->install();

                continue;
            }

            $installer->install($this->logger);
        }
    }
}
