<?php

namespace LaravelSyndication\Feeds\Concerns;

use Illuminate\Support\Collection;
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
     * @return static
     */
    public function author(array|Collection|Person $author): static
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get the author.
     *
     * @return Collection|array|Person|null
     */
    public function atomAuthor(): null|Collection|array|Person
    {
        return null;
    }

    /**
     * Test if there is an author present.
     *
     * @return bool
     */
    public function hasAuthor(): bool
    {
        return !empty($this->author);
    }

    /**
     * Set the contributors of an item.
     *
     * @param array|Collection|Person $contributor
     *
     * @return static
     */
    public function contributor(array|Collection|Person $contributor): static
    {
        $this->contributor = $contributor;
        return $this;
    }
}
