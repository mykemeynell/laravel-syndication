<?php

namespace LaravelSyndication\Feeds;

use LaravelSyndication\Feeds\Concerns;

abstract class RssFeed extends Feed
{
    use Concerns\UsesCloud,
        Concerns\UsesImages,
        Concerns\UsesLanguage,
        Concerns\UsesTtl;
}
