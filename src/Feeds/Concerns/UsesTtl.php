<?php

namespace LaravelSyndication\Feeds\Concerns;

use Carbon\CarbonInterval;

trait UsesTtl
{
    protected ?int $ttl = null;

    /**
     * Specifies the amount of time in minutes that the feed is valid for
     * before an application should make another request to the feed
     * source.
     *
     * @param int|CarbonInterval|null $ttl
     *
     * @return static
     */
    function ttl(null|int|CarbonInterval $ttl): static
    {
        if($ttl instanceof CarbonInterval) {
            $ttl = $ttl->totalMinutes;
        }

        $this->ttl = $ttl;
        return $this;
    }

    public function hasTtl(): bool
    {
        return !empty($this->ttl);
    }

    public function getTtl(): ?int
    {
        return $this->ttl;
    }
}
