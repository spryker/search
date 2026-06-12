<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Generated\Shared\Search;

use Spryker\Shared\Search\AbstractIndexMap;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF SEARCH MAP GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class MultiFieldIndexMap extends AbstractIndexMap
{

    const FULL_TEXT = 'full-text';
    const FULL_TEXT_NGRAM = 'full-text.ngram';
    const FULL_TEXT_BOOSTED = 'full-text-boosted';
    const FULL_TEXT_BOOSTED_NGRAM = 'full-text-boosted.ngram';

    /**
     * @var array
     */
    protected $metadata = [
        self::FULL_TEXT => [
            'search_analyzer' => 'ja_kuromoji_search_analyzer',
            'analyzer' => 'ja_kuromoji_index_analyzer',
        ],
        self::FULL_TEXT_NGRAM => [
            'type' => 'text',
            'search_analyzer' => 'ja_ngram_search_analyzer',
            'analyzer' => 'ja_ngram_index_analyzer',
        ],
        self::FULL_TEXT_BOOSTED => [
            'search_analyzer' => 'ja_kuromoji_search_analyzer',
            'analyzer' => 'ja_kuromoji_index_analyzer',
        ],
        self::FULL_TEXT_BOOSTED_NGRAM => [
            'type' => 'text',
            'search_analyzer' => 'ja_ngram_search_analyzer',
            'analyzer' => 'ja_ngram_index_analyzer',
        ],
    ];

}
