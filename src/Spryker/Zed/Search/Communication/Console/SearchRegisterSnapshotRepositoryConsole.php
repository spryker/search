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
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Communication\Console\ElasticsearchSnapshotRegisterRepositoryConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchRegisterSnapshotRepositoryConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:snapshot:register-repository';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will register a snapshot repository';

    /**
     * @var string
     */
    public const ARGUMENT_SNAPSHOT_REPOSITORY = 'snapshot-repository';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY, InputArgument::REQUIRED, 'Name of the snapshot repository.');

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
        /** @var string $snapshotRepository */
        $snapshotRepository = $input->getArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY);

        if ($this->getFacade()->existsSnapshotRepository($snapshotRepository)) {
            $this->info(sprintf('Snapshot repository "%s" already exists.', $snapshotRepository));

            return static::CODE_SUCCESS;
        }

        if ($this->getFacade()->createSnapshotRepository($snapshotRepository)) {
            $this->info(sprintf('Snapshot repository "%s" created.', $snapshotRepository));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Snapshot repository "%s" could not be created.', $snapshotRepository));

        return static::CODE_ERROR;
    }
}
