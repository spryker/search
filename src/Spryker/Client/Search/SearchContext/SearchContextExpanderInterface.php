<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\SearchContext;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SearchContextExpanderInterface
{
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer;
}
