<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Communication\Console\ElasticsearchCopyIndexConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchCopyIndexConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:index:copy';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will copy one index to another.';

    /**
     * @var string
     */
    public const ARGUMENT_SOURCE = 'source';

    /**
     * @var string
     */
    public const ARGUMENT_TARGET = 'target';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SOURCE, InputArgument::REQUIRED, 'Name of the source index to copy.');
        $this->addArgument(static::ARGUMENT_TARGET, InputArgument::REQUIRED, 'Name of the target index to copy source index to.');

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
        /** @var string $source */
        $source = $input->getArgument(static::ARGUMENT_SOURCE);
        /** @var string $target */
        $target = $input->getArgument(static::ARGUMENT_TARGET);

        if ($this->getFacade()->copyIndex($source, $target)) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
