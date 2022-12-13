<?php

namespace LaravelSyndication\Feeds\Concerns;

use LaravelSyndication\Feeds\Structure\FeedGenerator;

trait UsesGenerator
{
    protected ?string $generator = null;


    /**
     * Sets the generator, used to identify the program used to create the feed.
     *
     * @param string|FeedGenerator|null $generator
     *
     * @return $this
     */
    public function generator(null|string|FeedGenerator $generator): static
    {
        if(is_string($generator)) {
            $generator = new FeedGenerator(name: $generator);
        }

        $this->generator = $generator;
        return $this;
    }

    public function hasGenerator(): bool
    {
        return !empty($this->generator);
    }

    public function getGenerator(): ?string
    {
        return $this->generator;
    }
}
