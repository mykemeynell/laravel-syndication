<?php

namespace LaravelSyndication\Feeds\Concerns;

trait UsesCategories
{
    /**
     * The category term for the feed item entry.
     *
     * @var string|null
     */
    protected ?string $category = null;

    /**
     * Set the category term for the feed entry.
     *
     * @param string $term
     *
     * @return static
     */
    public function category(string $term): static
    {
        $this->category = $term;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function hasCategory()
    {
        return !empty($this->category);
    }
}
