<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\AtomFeed;
use LaravelSyndication\Feeds\RssAndAtomFeed;

trait ChecksAtomFeeds
{
    /**
     * Returns true if this feed is available in an Atom format.
     *
     * @return bool
     */
    public function hasAtomFeed(): bool
    {
        return $this instanceof AtomFeed || $this instanceof RssAndAtomFeed;
    }

    /**
     * Get the atom feed link for this feed.
     *
     * @return string
     */
    public function getAtomFeed(): string
    {
        return route('syndication', $this->identifier . '.atom');
    }
}
