<?php

namespace LaravelSyndication\Feeds\Concerns;

use Carbon\Carbon;
use LaravelSyndication\Feeds\FeedItem;

trait UsesTimestamps
{
    /**
     * The date that the feed item entry was originally published.
     *
     * @var Carbon|null
     */
    protected ?Carbon $published = null;

    /**
     * Indicates the last time the entry was modified in a significant way.
     * This value need not change after a typo is fixed, only after
     * a substantial modification. Generally, different
     * entries in a feed will have different updated timestamps.
     *
     * @var Carbon|null
     */
    protected ?Carbon $updated  = null;

    /**
     * The timestamp of when the feed item was published.
     *
     * @param Carbon|null $published
     *
     * @return FeedItem
     */
    public function published(?Carbon $published): FeedItem
    {
        $this->published = $published;
        return $this;
    }

    /**
     * Sets the modified timestamp of the feed item.
     *
     * @param Carbon $updated
     *
     * @return FeedItem
     */
    public function updated(Carbon $updated): FeedItem
    {
        $this->updated = $updated;
        return $this;
    }
}
