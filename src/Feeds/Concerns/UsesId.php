<?php

namespace LaravelSyndication\Feeds\Concerns;

use Illuminate\Support\Str;
use LaravelSyndication\Feeds\FeedItem;
use Ramsey\Uuid\Uuid;

trait UsesId
{
    /**
     * The ID of the feed item/entry. Used with Atom.
     *
     * Identifies the entry using a universally unique and permanent URI.
     * Suggestions on how to make a good id can be found here. Two
     * entries in a feed can have the same value for id if they represent
     * the same entry at different points in time.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#requiredEntryElements
     *
     * @var null|string|int|Uuid
     */
    protected null|string|int|Uuid $id = null;

    /**
     * Identifies the entry using a universally unique and permanent URI.
     * Two entries in a feed can have the same value for id if they
     * represent the same entry at different points in time.
     *
     * @param string|int|Uuid $idUri
     *
     * @see https://validator.w3.org/feed/docs/atom.html#requiredEntryElements
     *
     * @return FeedItem
     */
    public function id(string|int|Uuid $idUri): FeedItem
    {
        $this->id = $idUri;
        return $this;
    }

    /**
     * Get the FeedItem ID.
     *
     * @return int|Uuid|string|null
     * @throws \Exception
     */
    public function getId(): int|string|Uuid|null
    {
        if(empty($this->id) && empty($this->getUrl())) {
            throw new \Exception(sprintf("ID is a required attribute for Atom feed entries."));
        }

        if(empty($this->id) && !empty($this->getUrl())) {
            return $this->getUrl();
        }

        if($this->id instanceof Uuid || Str::isUuid($this->id)) {
            return sprintf("urn:uuid:%s", $this->id);
        }

        return $this->id;
    }
}
