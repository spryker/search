<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Generated\Shared\Search\PageIndexMap;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SpellingSuggestionQueryExpanderPlugin extends AbstractSuggestionExpanderPlugin
{

    const SUGGESTION_NAME = 'spelling-suggestion';

    const SIZE = 1;

    /**
     * @param \Elastica\Query $query
     * @param array $requestParameters
     *
     * @return \Elastica\Suggest\AbstractSuggest
     */
    protected function createCompletion(Query $query, array $requestParameters = [])
    {
        $termSuggest = $this->getFactory()
            ->createSuggestBuilder()
            ->createTerm(static::SUGGESTION_NAME, PageIndexMap::SUGGESTION_TERMS)
            ->setSize(static::SIZE);

        return $termSuggest;
    }

}