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
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Communication\Console\ElasticsearchDeleteIndexConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchDeleteIndexConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:index:delete';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will delete the search index.';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

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
        if ($this->getFacade()->getTotalCount() === 0) {
            $this->info('Search index is empty');

            return static::CODE_SUCCESS;
        }

        if ($this->getFacade()->delete()->isOk()) {
            $this->info('Search index deleted.');

            return static::CODE_SUCCESS;
        }

        $this->error('Search index could not be deleted.');

        return static::CODE_ERROR;
    }
}
