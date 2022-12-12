<?php

namespace LaravelSyndication\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string url()
 * @method static Collection meta(...$feeds)
 */
class Syndicate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'syndicate';
    }
}
