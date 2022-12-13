<?php

namespace LaravelSyndication\Feeds\Concerns;

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
     * @return $this
     */
    public function copyright(string $copyright): static
    {
        $this->copyright = $copyright;
        return $this;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function hasCopyright(): bool
    {
        return !empty($this->copyright);
    }
}
