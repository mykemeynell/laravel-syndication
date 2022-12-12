<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\FeedItem;
use LaravelSyndication\Feeds\Structure\Items\Atom\Source;

trait UsesSources
{
    /**
     * Contains metadata from the source feed if this entry is a copy.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalEntryElements
     * @var Source|null
     */
    protected ?Source $source = null;

    /**
     * Specifies the source of a feed item, if this feed item is a copy.
     *
     * @param Source $source
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalEntryElements
     * @return FeedItem
     */
    public function source(Source $source): FeedItem
    {
        $this->source = $source;
        return $this;
    }
}
