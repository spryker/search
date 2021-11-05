<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Communication\Console\ElasticsearchCloseIndexConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchCloseIndexConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:index:close';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will close an index.';

    /**
     * @var string
     */
    public const OPTION_ALL = 'all';

    /**
     * @var string
     */
    public const OPTION_ALL_SHORT = 'a';

    /**
     * @var string
     */
    public const OPTION_ALL_DESCRIPTION = 'If set to this command will work on all indices (_all) instead of the environment specific one.';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->addOption(static::OPTION_ALL, static::OPTION_ALL_SHORT, InputOption::VALUE_NONE, static::OPTION_ALL_DESCRIPTION);

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
        if ($input->getOption(static::OPTION_ALL)) {
            return $this->closeAll();
        }

        return $this->close();
    }

    /**
     * @return int
     */
    protected function close()
    {
        if ($this->getFacade()->closeIndex()) {
            $this->info('Search index closed.');

            return static::CODE_SUCCESS;
        }

        $this->error('Search index could not be closed.');

        return static::CODE_ERROR;
    }

    /**
     * @return int
     */
    protected function closeAll()
    {
        if ($this->getFacade()->closeAllIndices()) {
            $this->info('Search indices closed.');

            return static::CODE_SUCCESS;
        }

        $this->error('Search indices could not be closed.');

        return static::CODE_ERROR;
    }
}
