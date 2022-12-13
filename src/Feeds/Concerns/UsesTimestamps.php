<?php

namespace LaravelSyndication\Feeds\Concerns;

use Carbon\Carbon;

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
     * @return static
     */
    public function published(?Carbon $published): static
    {
        $this->published = $published;
        return $this;
    }

    public function hasPublished(): bool
    {
        return !empty($this->published);
    }

    public function getPublished(): ?Carbon
    {
        return $this->published;
    }

    /**
     * Sets the modified timestamp of the feed item.
     *
     * @param Carbon $updated
     *
     * @return static
     */
    public function updated(Carbon $updated): static
    {
        $this->updated = $updated;
        return $this;
    }

    public function hasUpdated(): bool
    {
        return !empty($this->updated);
    }

    public function getUpdated(): ?Carbon
    {
        return $this->updated;
    }
}
