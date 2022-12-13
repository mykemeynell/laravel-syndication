<?php

namespace LaravelSyndication\Feeds\Concerns;

use Illuminate\Support\Collection;
use LaravelSyndication\Feeds\Structure\Atom\Person;

trait UsesAuthors
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
    protected null|Collection|array|Person $contributors = null;

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
     * Set the contributors of an item.
     *
     * @param array|Collection|Person $contributor
     *
     * @return static
     */
    public function contributors(array|Collection|Person $contributor): static
    {
        $this->contributors = $contributor;
        return $this;
    }

    public function getAuthor(): Person|array|Collection|null
    {
        return $this->author;
    }

    public function hasAuthor(): bool
    {
        return !empty($this->author);
    }

    public function getContributors(): Person|array|Collection|null
    {
        return $this->contributors;
    }

    public function hasContributors(): bool
    {
        return !empty($this->contributors);
    }
}
