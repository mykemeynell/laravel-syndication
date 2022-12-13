<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesLanguage
{
    protected ?string $language = null;

    /**
     * Identifies the language.
     *
     * @param string|null $language
     *
     * @return static
     */
    public function language(?string $language): static
    {
        $this->language = $language;
        return $this;
    }

    public function hasLanguage(): bool
    {
        return !empty($this->language);
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }
}
