<?php

namespace LaravelSyndication\Feeds\Structure\Items\Atom;

use Carbon\Carbon;
use LaravelSyndication\Feeds\Structure\Atom\Person;

class Source
{
    public string $id;
    public string $title;
    public Carbon $updated;
    public null|Person $author;

    function __construct(string $id, string $title, Carbon $updated, ?Person $author = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->updated = $updated;
        $this->author = $author;
    }

    /**
     * Test if the source has an author.
     *
     * @return bool
     */
    public function hasAuthor(): bool
    {
        return !empty($this->author);
    }
}
