<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryMapTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;
use Zend\Filter\Word\UnderscoreToDash;

class PageDataMapper implements PageDataMapperInterface
{

    const FACET_NAME = 'facet-name';
    const FACET_VALUE = 'facet-value';
    const ALL_PARENTS = 'all-parents';
    const DIRECT_PARENTS = 'direct-parents';

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface
     */
    protected $pageMapBuilder;

    /**
     * @var \Zend\Filter\Word\CamelCaseToDash
     */
    protected $underscoreToDashFilter;

    /**
     * @var \Generated\Shared\Search\PageIndexMap
     */
    protected $pageIndexMap;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     */
    public function __construct(PageMapBuilderInterface $pageMapBuilder)
    {
        $this->pageMapBuilder = $pageMapBuilder;
        $this->underscoreToDashFilter = new UnderscoreToDash();
        $this->pageIndexMap = new PageIndexMap();
    }

    /**
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapData(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer)
    {
        $result = [];

        $pageMapTransfer = $pageMap->buildPageMap($this->pageMapBuilder, $data, $localeTransfer);

        foreach ($pageMapTransfer->modifiedToArray() as $key => $value) {
            $normalizedKey = $this->normalizeKey($key);

            $result = $this->mapValue($pageMapTransfer, $normalizedKey, $value, $result);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException
     *
     * @return string
     */
    protected function normalizeKey($key)
    {
        if (in_array($key, $this->pageIndexMap->getProperties())) {
            return $key;
        }

        $normalizedKey = $this->underscoreToDashFilter->filter($key);

        if (in_array($normalizedKey, $this->pageIndexMap->getProperties())) {
            return $normalizedKey;
        }

        throw new InvalidPropertyNameException(sprintf('Unable to map %s property in %s', $key, get_class($this->pageIndexMap)));
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $normalizedKey
     * @param mixed $value
     * @param array $result
     *
     * @return array
     */
    protected function mapValue(PageMapTransfer $pageMapTransfer, $normalizedKey, $value, array $result)
    {
        switch ($normalizedKey) {
            case PageIndexMap::SEARCH_RESULT_DATA:
                $result = $this->transformSearchResultData($result, $pageMapTransfer->getSearchResultData());
                break;
            case PageIndexMap::STRING_FACET:
                $result = $this->transformStringFacet($result, $pageMapTransfer->getStringFacet());
                break;
            case PageIndexMap::INTEGER_FACET:
                $result = $this->transformIntegerFacet($result, $pageMapTransfer->getIntegerFacet());
                break;
            case PageIndexMap::STRING_SORT:
                $result = $this->transformStringSort($result, $pageMapTransfer->getStringSort());
                break;
            case PageIndexMap::INTEGER_SORT:
                $result = $this->transformIntegerSort($result, $pageMapTransfer->getIntegerSort());
                break;
            case PageIndexMap::CATEGORY:
                $result = $this->transformCategory($result, $pageMapTransfer->getCategory());
                break;
            default:
                $result = $this->transformOther($result, $normalizedKey, $value);
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\SearchResultDataMapTransfer[] $searchResultData
     *
     * @return array
     */
    protected function transformSearchResultData(array $result, $searchResultData)
    {
        foreach ($searchResultData as $searchResultDataTransfer) {
            $searchResultDataTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::SEARCH_RESULT_DATA][$searchResultDataTransfer->getName()] = $searchResultDataTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\StringFacetMapTransfer[] $stringFacetMap
     *
     * @return array
     */
    protected function transformStringFacet(array $result, $stringFacetMap)
    {
        foreach ($stringFacetMap as $stringFacetMapTransfer) {
            $stringFacetMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::STRING_FACET][] = [
                self::FACET_NAME => $stringFacetMapTransfer->getName(),
                self::FACET_VALUE => $stringFacetMapTransfer->getValue(),
            ];
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\IntegerFacetMapTransfer[] $integerFacet
     *
     * @return array
     */
    protected function transformIntegerFacet(array $result, $integerFacet)
    {
        foreach ($integerFacet as $integerFacetMapTransfer) {
            $integerFacetMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::INTEGER_FACET][] = [
                self::FACET_NAME => $integerFacetMapTransfer->getName(),
                self::FACET_VALUE => $integerFacetMapTransfer->getValue(),
            ];
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\StringSortMapTransfer[] $stringSortMap
     *
     * @return array
     */
    protected function transformStringSort(array $result, $stringSortMap)
    {
        foreach ($stringSortMap as $stringSortMapTransfer) {
            $stringSortMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::STRING_SORT][$stringSortMapTransfer->getName()] = $stringSortMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\IntegerSortMapTransfer[] $integerSortMap
     *
     * @return array
     */
    protected function transformIntegerSort(array $result, $integerSortMap)
    {
        foreach ($integerSortMap as $stringSortMapTransfer) {
            $stringSortMapTransfer
                ->requireName()
                ->requireValue();

            $result[PageIndexMap::INTEGER_SORT][$stringSortMapTransfer->getName()] = $stringSortMapTransfer->getValue();
        }

        return $result;
    }

    /**
     * @param array $result
     * @param \Generated\Shared\Transfer\CategoryMapTransfer $categoryMap
     *
     * @return array
     */
    protected function transformCategory(array $result, CategoryMapTransfer $categoryMap)
    {
        $categoryMap
            ->requireAllParents()
            ->requireDirectParents();

        $result[PageIndexMap::CATEGORY] = [
            self::ALL_PARENTS => $categoryMap->getAllParents(),
            self::DIRECT_PARENTS => $categoryMap->getDirectParents(),
        ];

        return $result;
    }

    /**
     * @param array $result
     * @param string $key
     * @param mixed $value
     *
     * @return array
     */
    protected function transformOther(array $result, $key, $value)
    {
        $result[$key] = $value;

        return $result;
    }

}
