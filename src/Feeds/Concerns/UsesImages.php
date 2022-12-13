<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\Structure\FeedImage;

trait UsesImages
{
    /**
     * Image.
     *
     * @var FeedImage|null
     */
    protected ?FeedImage $image = null;

    /**
     * Set the image.
     *
     * @param FeedImage|null $image
     *
     * @return $this
     */
    public function image(?FeedImage $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function getImage(): ?FeedImage
    {
        return $this->image;
    }
}
