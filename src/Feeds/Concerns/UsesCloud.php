<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\Structure\FeedCloud;

trait UsesCloud
{
    protected ?FeedCloud $cloud = null;

    /**
     * Set the value of what will become the <cloud> tag.
     *
     * RSS Only.
     *
     * @param FeedCloud $cloud
     *
     * @return $this
     */
    public function cloud(FeedCloud $cloud): static
    {
        $this->cloud = $cloud;
        return $this;
    }

    public function getCloud(): ?FeedCloud
    {
        return $this->cloud;
    }

    public function hasCloud(): bool
    {
        return !empty($this->cloud);
    }
}
