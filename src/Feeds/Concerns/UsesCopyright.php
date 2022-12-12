<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\FeedItem;

trait UsesCopyright
{
    /**
     * Copyright specific to the feed item entry.
     *
     * @var string|null
     */
    protected ?string $copyright = null;

    /**
     * Copyright of the feed item entry.
     *
     * @param string $copyright
     *
     * @return FeedItem
     */
    public function copyright(string $copyright): FeedItem
    {
        $this->copyright = $copyright;
        return $this;
    }
}
