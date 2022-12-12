<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\FeedItem;
use LaravelSyndication\Feeds\Structure\Atom\Content;
use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;

trait UsesContent
{
    /**
     * Contains the content or links back to the main content of the item.
     * Only used with Atom.
     *
     * @var string|null
     */
    protected null|string|Content $content = null;

    /**
     * Enclosure of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_enclosure.asp
     * @var Enclosure|null
     */
    protected ?Enclosure $enclosure = null;

    /**
     * Contains or links to the complete content of the entry. Content must
     * be provided if there is no alternate link, and should be provided
     * if there is no summary.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#recommendedEntryElements
     *
     * @param string|null $content
     *
     * @return FeedItem
     */
    public function content(null|string|Content $content): FeedItem
    {
        $this->content = !$content instanceof Content
            ? new Content(contents: $content)
            : $content;

        return $this;
    }

    /**
     * Will test if an enclosure has been passed, and if so - generate the
     * appropriate <content> tag.
     *
     * @return FeedItem
     */
    public function contentFromEnclosure(): FeedItem
    {
        if(!$this->hasEnclosure()) {
            return $this;
        }

        return $this->content(Content::fromEnclosure($this->enclosure));
    }

    /**
     * Set the enclosure of the feed item.
     *
     * @param string      $url
     * @param int|null    $length
     * @param string|null $type
     * @param string|null $filename
     *
     * @return FeedItem
     * @throws \Exception
     */
    public function enclosure(string $url, ?string $type = null, ?string $filename = null, ?int $length = null): FeedItem
    {
        $this->enclosure = new Enclosure(
            url: $url,
            length: $length,
            type: $type,
            filename: $filename
        );

        return $this;
    }

    /**
     * Test if an enclosure has been set.
     *
     * @return bool
     */
    public function hasEnclosure(): bool
    {
        return !empty($this->enclosure) && $this->enclosure instanceof Enclosure;
    }
}
