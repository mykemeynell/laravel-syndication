<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesDescription
{
    /**
     * Description of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_title_link_description_item.asp
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * Description of the feed.
     *
     * @param string|null $description
     *
     * @return $this
     */
    function description(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasDescription(): bool
    {
        return !empty($this->description);
    }
}
