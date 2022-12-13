<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesUrl
{
    /**
     * URL of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_title_link_description_item.asp
     * @var string|null
     */
    protected ?string $url = null;

    /**
     * Set the URL of the feed item.
     *
     * @param string $url
     *
     * @return static
     */
    public function url(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function hasUrl(): bool
    {
        return !empty($this->url);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
