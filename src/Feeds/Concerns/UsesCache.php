<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesCache
{
    public function cacheKey(): string
    {
        return sprintf("%s.%s", $this->identifier, $this->requestedFeedType);
    }
}
