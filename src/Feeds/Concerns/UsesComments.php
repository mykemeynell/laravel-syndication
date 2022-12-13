<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\FeedItem;

trait UsesComments
{
    /**
     * Comments URL of the feed item.
     *
     * @var string|null
     */
    protected ?string $comments = null;

    /**
     * Set the comments URL of the feed item.
     *
     * @param string $commentsUrl
     *
     * @return static
     */
    public function comments(string $commentsUrl): static
    {
        $this->comments = $commentsUrl;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function hasComments(): bool
    {
        return !empty($this->comments);
    }
}
