<?php

namespace LaravelSyndication\Contracts\Models;

use LaravelSyndication\Feeds\FeedItem;

interface IsSyndicationItem
{
    function toFeedItem(): FeedItem;
}
