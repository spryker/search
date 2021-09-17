<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition;

interface IndexDefinitionMapperInterface
{
    /**
     * @param array<\Symfony\Component\Finder\SplFileInfo> $splFiles
     *
     * @return array<\Generated\Shared\Transfer\IndexDefinitionFileTransfer>
     */
    public function mapSplFilesToIndexDefinitionFileTransfers(array $splFiles): array;
}
