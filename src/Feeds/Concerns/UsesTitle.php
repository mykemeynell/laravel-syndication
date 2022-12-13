<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesTitle
{
    protected ?string $title = null;

    /**
     * Title for the feed.
     *
     * @return $this
     */
    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function hasTitle(): bool
    {
        return !empty($this->title);
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
