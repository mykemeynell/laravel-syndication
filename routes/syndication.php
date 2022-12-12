<?php

use Illuminate\Support\Facades\Route;

$options = [
    'prefix' => config('syndication.routing.prefix')
];

if($domain = config('syndication.routing.domain') !== null) {
    $options['domain'] = $domain;
}

Route::group($options, function () {
    Route::get('/{feed}', [
        \LaravelSyndication\Http\Controllers\SyndicationController::class, 'generate'
    ])->name('syndication');
});
