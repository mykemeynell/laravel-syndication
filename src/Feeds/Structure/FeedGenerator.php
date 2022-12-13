<?php

namespace LaravelSyndication\Feeds\Structure;

class FeedGenerator
{
    public string $name;
    public ?string $uri;
    public ?string $version;

    public function __construct(string $name, ?string $uri = null, ?string $version = null)
    {
        $this->name = $name;
        $this->uri = $uri;
        $this->version = $version;
    }

    public function __toString(): string
    {
        return view('syndication::tags.generator')
            ->with('generator', $this)
            ->render();
    }
}
