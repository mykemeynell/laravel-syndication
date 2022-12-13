<?php

namespace LaravelSyndication\Feeds;

use LaravelSyndication\Feeds\Concerns;
abstract class RssAndAtomFeed extends Feed
{
    use Concerns\ChecksAtomFeeds,
        Concerns\UsesId,
        Concerns\FetchesId,
        Concerns\UsesAuthors,
        Concerns\UsesCloud,
        Concerns\UsesImages,
        Concerns\UsesIconsAndLogos,
        Concerns\UsesLanguage,
        Concerns\UsesTtl,
        Concerns\UsesSubtitle;
}
