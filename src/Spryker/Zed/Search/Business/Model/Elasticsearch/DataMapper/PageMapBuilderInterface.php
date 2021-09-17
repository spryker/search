<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Transfer\PageMapTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface PageMapBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $fieldName
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function add(PageMapTransfer $pageMapTransfer, $fieldName, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function addSearchResultData(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array|string $value
     *
     * @return $this
     */
    public function addFullText(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array|string $value
     *
     * @return $this
     */
    public function addFullTextBoosted(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array|string $value
     *
     * @return $this
     */
    public function addSuggestionTerms(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array|string $value
     *
     * @return $this
     */
    public function addCompletionTerms(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param array|string $value
     *
     * @return $this
     */
    public function addStringFacet(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param array|int $value
     *
     * @return $this
     */
    public function addIntegerFacet(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addStringSort(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param int $value
     *
     * @return $this
     */
    public function addIntegerSort(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $allParents
     * @param array $directParents
     *
     * @return $this
     */
    public function addCategory(PageMapTransfer $pageMapTransfer, array $allParents, array $directParents);
}
