<?php

namespace LaravelSyndication\Feeds\Structure\Atom;

/**
 * @see https://validator.w3.org/feed/docs/atom.html#person
 */
class Person
{
    public ?string $name;
    public ?string $email;
    public ?string $uri;

    public function __construct(?string $name = null, ?string $email = null, ?string $uri = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->uri = $uri;
    }

    public function hasName(): bool
    {
        return !empty($this->name);
    }

    public function hasEmail(): bool
    {
        return !empty($this->email);
    }

    public function hasUri(): bool
    {
        return !empty($this->uri);
    }
}
