<?php

namespace LaravelSyndication\Contracts\Feeds;

use LaravelSyndication\Feeds\Structure\FeedImage;

interface SyndicatesWithImage
{
    function image(): FeedImage;
}
