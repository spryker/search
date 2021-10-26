<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Plugin\Elasticsearch\QueryExpander;

use Elastica\Query;
use Elastica\Suggest;
use Elastica\Suggest\Term;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Exception\MissingSuggestionQueryException;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SpellingSuggestionQueryExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Search
 * @group Plugin
 * @group Elasticsearch
 * @group QueryExpander
 * @group SpellingSuggestionQueryExpanderPluginTest
 * Add your own group annotations below this line
 */
class SpellingSuggestionQueryExpanderPluginTest extends AbstractQueryExpanderPluginTest
{
    /**
     * @return void
     */
    public function testCompletionQueryExpanderShouldThrowExceptionWhenBaseQueryDoesntSupportSuggest(): void
    {
        $this->expectException(MissingSuggestionQueryException::class);
        $baseQueryPlugin = $this->createBaseQueryPlugin();

        $queryExpander = new SpellingSuggestionQueryExpanderPlugin();

        $queryExpander->expandQuery($baseQueryPlugin);
    }

    /**
     * @dataProvider suggestionQueryExpanderDataProvider
     *
     * @param \Elastica\Query $expectedQuery
     * @param \Elastica\Suggest $expectedSuggest
     *
     * @return void
     */
    public function testSuggestionQueryExpanderShouldExpandTheBaseQueryWithAggregation(Query $expectedQuery, Suggest $expectedSuggest): void
    {
        $queryExpander = new SpellingSuggestionQueryExpanderPlugin();

        $baseQuery = $this->createBaseQueryPlugin();
        $baseQuery->getSearchQuery()->setSuggest($expectedSuggest);

        $query = $queryExpander->expandQuery($baseQuery);

        $query = $query->getSearchQuery();

        $this->assertEquals($expectedQuery, $query);
    }

    /**
     * @return array
     */
    public function suggestionQueryExpanderDataProvider(): array
    {
        return [
            'simple suggestion query' => $this->getDataForSimpleSuggestionQuery(),
            'empty suggestion query' => $this->getDataForEmptySuggestionQuery(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataForSimpleSuggestionQuery(): array
    {
        /** @var \Elastica\Query $expectedQuery */
        $expectedQuery = $this
            ->createBaseQueryPlugin()
            ->getSearchQuery();

        $expectedTermSuggest = new Term(SpellingSuggestionQueryExpanderPlugin::SUGGESTION_NAME, PageIndexMap::SUGGESTION_TERMS);
        $expectedTermSuggest->setSize(SpellingSuggestionQueryExpanderPlugin::SIZE);

        $expectedSuggest = new Suggest();
        $expectedSuggest->setGlobalText('foo');
        $expectedSuggest->addSuggestion($expectedTermSuggest);

        $expectedQuery->setSuggest($expectedSuggest);

        return [$expectedQuery, $expectedSuggest];
    }

    /**
     * @return array
     */
    protected function getDataForEmptySuggestionQuery(): array
    {
        /** @var \Elastica\Query $expectedQuery */
        $expectedQuery = $this
            ->createBaseQueryPlugin()
            ->getSearchQuery();

        $expectedSuggest = new Suggest();
        $expectedSuggest->setGlobalText('');

        $expectedQuery->setSuggest($expectedSuggest);

        return [$expectedQuery, $expectedSuggest];
    }
}
