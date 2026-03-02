<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator\Adapter;

/**
 * @deprecated Will be removed without replacement.
 */
interface SearchDelegatorAdapterInterface
{
    /**
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read(string $key, ?string $typeName = null, ?string $indexName = null);

    public function write(array $documentDataSet, ?string $typeName = null, ?string $indexName = null): bool;

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool;

    public function delete(array $documentDataSet, ?string $typeName = null, ?string $indexName = null): bool;

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool;
}
