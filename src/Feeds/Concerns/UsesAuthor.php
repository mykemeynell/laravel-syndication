<?php

namespace LaravelSyndication\Feeds\Concerns;

use Illuminate\Support\Collection;
use LaravelSyndication\Feeds\FeedItem;
use LaravelSyndication\Feeds\Structure\Atom\Person;

trait UsesAuthor
{
    /**
     * The author(s).
     *
     * @see https://www.w3schools.com/xml/rss_tag_author.asp
     * @see https://validator.w3.org/feed/docs/atom.html#person
     * @var array|Collection|Person|null
     */
    protected null|Collection|array|Person $author = null;

    /**
     * The contributors(s).
     *
     * @see https://www.w3schools.com/xml/rss_tag_author.asp
     * @see https://validator.w3.org/feed/docs/atom.html#person
     * @var array|Collection|Person|null
     */
    protected null|Collection|array|Person $contributor = null;

    /**
     * Set the authors of an item.
     *
     * @param array|Collection|Person $author
     *
     * @return FeedItem
     */
    public function author(array|Collection|Person $author): FeedItem
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set the contributors of an item.
     *
     * @param array|Collection|Person $contributor
     *
     * @return FeedItem
     */
    public function contributor(array|Collection|Person $contributor): FeedItem
    {
        $this->contributor = $contributor;
        return $this;
    }
}
