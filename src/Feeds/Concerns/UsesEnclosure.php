<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;

trait UsesEnclosure
{
    /**
     * Enclosure of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_enclosure.asp
     * @var Enclosure|null
     */
    protected ?Enclosure $enclosure = null;

    /**
     * Set the enclosure of the feed item.
     *
     * @param string      $url
     * @param int|null    $length
     * @param string|null $type
     * @param string|null $filename
     *
     * @return static
     * @throws \Exception
     */
    public function enclosure(string $url, ?string $type = null, ?string $filename = null, ?int $length = null): static
    {
        $this->enclosure = new Enclosure(
            url: $url,
            type: $type,
            filename: $filename,
            length: $length
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

    public function getEnclosure(): ?Enclosure
    {
        return $this->enclosure;
    }
}
