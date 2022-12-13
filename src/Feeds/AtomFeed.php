<?php

namespace LaravelSyndication\Feeds;

use LaravelSyndication\Feeds\Concerns;

abstract class AtomFeed extends Feed
{
    use Concerns\ChecksAtomFeeds,
        Concerns\UsesId,
        Concerns\FetchesId,
        Concerns\UsesAuthors,
        Concerns\UsesIconsAndLogos,
        Concerns\UsesSubtitle;
}
