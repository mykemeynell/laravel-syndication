<?php

namespace LaravelSyndication\Feeds\Structure\Atom;

use Illuminate\Support\Str;
use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;

class Content
{
    public ?string $type = null;
    public ?string $src = null;
    public ?string $contents = null;

    public function __construct(?string $type = null, ?string $src = null, ?string $contents = null)
    {
        $this->type = $type;
        $this->src = $src;
        $this->contents = $contents;
    }

    public static function fromEnclosure(Enclosure $enclosure): Content
    {
        return new self(
            type: $enclosure->type(),
            src: $enclosure->url(),
            contents: $enclosure->contents()
        );
    }

    public static function fromString(?string $string): Content
    {
        return new self(type: 'text', contents: $string);
    }

    public function fromUri(?string $uri): Content
    {
        return new self(src: $uri);
    }

    public function __toString(): string
    {
        return view('syndication::tags.atom.content')
            ->with('content', $this);
    }
}
