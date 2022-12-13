<?php

namespace LaravelSyndication\Feeds;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelSyndication\Contracts\Feeds\AtomFeed;
use LaravelSyndication\Feeds\Concerns\FetchesId;
use LaravelSyndication\Feeds\Concerns\UsesAuthors;
use LaravelSyndication\Feeds\Concerns\UsesCategories;
use LaravelSyndication\Feeds\Concerns\UsesComments;
use LaravelSyndication\Feeds\Concerns\UsesContent;
use LaravelSyndication\Feeds\Concerns\UsesCopyright;
use LaravelSyndication\Feeds\Concerns\UsesDescription;
use LaravelSyndication\Feeds\Concerns\UsesEnclosure;
use LaravelSyndication\Feeds\Concerns\UsesId;
use LaravelSyndication\Feeds\Concerns\UsesMagicCaller;
use LaravelSyndication\Feeds\Concerns\UsesSources;
use LaravelSyndication\Feeds\Concerns\UsesTimestamps;
use LaravelSyndication\Feeds\Concerns\UsesTitle;
use LaravelSyndication\Feeds\Concerns\UsesUrl;
use LaravelSyndication\Feeds\Structure\Atom\Person;
use LaravelSyndication\Feeds\Structure\Items\Atom\Source;
use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;
use Ramsey\Uuid\Uuid;

class FeedItem
{
    use UsesCategories, UsesCopyright, UsesId, FetchesId, UsesContent, UsesComments,
        UsesAuthors, UsesTimestamps, UsesSources, UsesTitle, UsesDescription,
        UsesUrl, UsesEnclosure;

    /**
     * Set the FeedItem using an associative array.
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        foreach($config as $property => $value) {
            if(!property_exists($this, $property)) {
                throw new \Exception(sprintf("Attempting to set unknown property [%s] on [%s]", $property, self::class));
            }

            $this->{$property} = $value;
        }
    }
}
