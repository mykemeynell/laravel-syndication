<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesSubtitle
{
    /**
     * The subtitle.
     *
     * @var string|null
     */
    protected ?string $subtitle = null;

    /**
     * Contains a human-readable description or subtitle for the feed.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     * @see https://validator.w3.org/feed/docs/atom.html#text
     * @return $this
     */
    function subtitle(?string $subtitle = null): static
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function hasSubtitle(): bool
    {
        return !empty($this->subtitle) ||
            (method_exists($this, 'hasDescription') && $this->hasDescription());
    }

    public function getSubtitle(): ?string
    {
        if($this->hasSubtitle()) {
            return $this->subtitle;
        }

        return method_exists($this, 'getDescription')
            ? $this->getDescription() : null;
    }
}
