<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:setup';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will run installer for search';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
        $this->setAliases(['setup:search']);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->install($this->getMessenger());

        return static::CODE_SUCCESS;
    }
}
