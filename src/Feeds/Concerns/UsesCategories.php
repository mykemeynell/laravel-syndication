<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\FeedItem;

trait UsesCategories
{
    /**
     * The category term for the feed item entry.
     *
     * @var string|null
     */
    protected ?string $category = null;

    /**
     * Set the category term for the feed entry.
     *
     * @param string $term
     *
     * @return FeedItem
     */
    public function category(string $term): FeedItem
    {
        $this->category = $term;
        return $this;
    }
}
