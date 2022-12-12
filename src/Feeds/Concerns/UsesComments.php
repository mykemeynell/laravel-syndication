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
     * @return FeedItem
     */
    public function comments(string $commentsUrl): FeedItem
    {
        $this->comments = $commentsUrl;
        return $this;
    }
}
