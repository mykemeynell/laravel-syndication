<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\Structure\Atom\Content;
use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;

trait UsesContent
{
    /**
     * Contains the content or links back to the main content of the item.
     * Only used with Atom.
     *
     * @var string|Content|null
     */
    protected null|string|Content $content = null;

    /**
     * Contains or links to the complete content of the entry. Content must
     * be provided if there is no alternate link, and should be provided
     * if there is no summary.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#recommendedEntryElements
     *
     * @param string|Content|null $content
     *
     * @return static
     */
    public function content(null|string|Content $content): static
    {
        $this->content = !$content instanceof Content
            ? new Content(contents: $content)
            : $content;

        return $this;
    }

    public function getContent(): Content|string|null
    {
        return $this->content;
    }

    public function hasContent(): bool
    {
        return !empty($this->content);
    }

    /**
     * Will test if an enclosure has been passed, and if so - generate the
     * appropriate <content> tag.
     *
     * @return static
     */
    public function contentFromEnclosure(): static
    {
        if(!property_exists($this, 'enclosure') || empty($this->enclosure)) {
            return $this;
        }

        return $this->content(Content::fromEnclosure($this->enclosure));
    }
}
