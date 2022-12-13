<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesIconsAndLogos
{
    protected ?string $icon = null;
    protected ?string $logo = null;

    /**
     * Identifies a larger image which provides visual identification for the
     * feed. Images should be twice as wide as they are tall.
     *
     * @param string|null $logo
     *
     * @return static
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     */
    public function logo(?string $logo): static
    {
        $this->logo = $logo;
        return $this;
    }

    public function hasLogo(): bool
    {
        return !empty($this->logo);
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Identifies a small image which provides iconic visual identification
     * for the feed. Icons should be square.
     *
     * @param string|null $icon
     *
     * @return static
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     */
    public function icon(?string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function hasIcon(): bool
    {
        return !empty($this->icon);
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
