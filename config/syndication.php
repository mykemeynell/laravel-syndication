<?php

return [
    /*
     * Feeds that can be used for syndication are defined here. This should be
     * done using a key-value pair -- where the key is the identifier
     * of the feed, adn the value is the feed object.
     *
     * For example:
     *  'posts' => App\Feeds\Posts::class
     */
    'feeds' => [],

    /*
     * Routing options for the generated /feed/ routes.
     */
    'routing' => [
        'prefix' => 'feeds',
        'domain' => null,
    ],

    /*
     * Encoding for the generated files, it will default to utf8 if there is no
     * other value specified -- so if you'd like a simpler config
     * file, then feel free to remove this key.
     */
    'encoding' => 'utf-8',

    /*
     * Whether results from generated feeds are cached at all.
     */
    'cache_feeds' => true,

    /*
     * Cache store, null will use the default cache store.
     */
    'cache_store' => null,

    /*
     * Number of minutes any generated feeds are valid for before a new request
     * will generate a new feed and write to disk.
     */
    'cache_ttl' => 1440,

    /*
     * If you would like to specify different TTLs for different feeds, then
     * you can do this here. It should be keyed using the same keys defined in
     * the 'feeds' key array, appended with the type of feed, for example;
     *  - 'podcasts.atom' or 'podcasts.rss'
     *
     * To disable caching for specific feeds, set the value to false.
     *
     * To disable caching for all feeds except those specified, set
     * 'cache_feeds' to false, and specify a value greater than 0 in 'caching'.
     */
    'caching' => [
        // 'posts.atom' => 10080
    ],
];
