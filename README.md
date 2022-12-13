# Laravel Syndication

A super simple RSS and Atom feed package for Laravel. Can generate either RSS,
Atom or both - for specified models.

- [Installation](#installation)
    - [With auto-discover](#with-auto-discovery)
    - [Without auto-discover](#without-auto-discovery)
- [Publishing the configuration](#publish-the-configuration)
- [Config](#config)
    - [Feeds](#feeds)
    - [Routing](#routing)
    - [Encoding](#encoding)
- [Usage](#usage)
    - [Creating a new feed](#creating-a-new-feed)
    - [Feed configuration options](#configuring-a-feed)
    - [Feed model configuration](#configuring-the-feed-model)
    - [Fully configured model example](#fully-configured-model-example)
    - [Additional feed configuration options](#additional-feed-configuration-options)
- [Outputting meta tags to view](#outputting-meta-tags-to-view)

## Installation

```
composer require mykemeynell/laravel-syndication
```

### With auto-discovery

The service provider has already been registered, so you can continue to the next step.

### Without auto-discovery

Add the `LaravelSyndicationServiceProvider` to your `config/app.php` file.

```php
    'providers' => [
        // ...
        LaravelSyndication\LaravelSyndicationServiceProvider::class,
        // ...    
    ],
```

## Publish the configuration

This will create a new configuration file under `config/syndication.php`.

```
php artisan vendor:publish --tag=laravel-syndication
```

# Config

# Feeds

```php
/*
 * Feeds that can be used for syndication are defined here. This should be
 * done using a key-value pair -- where the key is the identifier
 * of the feed, adn the value is the feed object.
 *
 * For example:
 *  'posts' => App\Feeds\Posts::class
 */
'feeds' => [],
```

## Routing

```php
'routing' => [
    /*
     *  Sets the prefix of any generated feeds.
     * default is 'feeds'.
     */
    'prefix' => 'feeds',
    
    /*
     * If you'd like to serve your feeds from a separate domain
     * or subdomain - you can specify the domain here.
     */ 
    'domain' => null,
],
```

## Encoding

```php
/*
 * Encoding for the generated files, it will default to utf8 if there is no
 * other value specified -- so if you'd like a simpler config
 * file, then feel free to remove this key.
 */
'encoding' => 'utf-8',
```

## Caching

Sets the default caching value for all feeds. If set to false, nothing will be
cached and no values current cached will be read.

```php
'cache_feeds' => true,
```

Specify the cache store to use. Using `null` will default to the default cache
store.

```php
'cache_store' => null,
```

How long (in minutes) the cache is valid for.

```php
'cache_ttl' => 1440,
```

If you would like to specify different TTLs for different feeds, then
you can do this here. It should be keyed using the same keys defined in
the `feeds` key array, appended with the type of feed, for example;
`'podcasts.atom'` or `'podcasts.rss'`

To disable caching for specific feeds, set the value to `false`.

To disable caching for all feeds except those specified, set
`cache_feeds` to false, and specify a value greater than `0` in `caching`.

```php
'caching' => [
    'posts.atom' => 10080
],
```

# Usage

## Creating a new feed

### Use artisan to create a new feed class.

For example, if you wanted to create a feed for podcasts;

```
php artisan make:feed Postcasts
```

This will output a feed class object at `app/Feeds/Postcasts.php`.

### Add the feed class to `config/syndication.php`

Under the `feeds` key, add the FQN of the feed you just created.

```php
'feeds' => [
    // ...
    'podcasts' => App\Feeds\Postcasts::class
    // ...
],
```

### Configuring a `Feed`

#### Feed Types

Use the following base classes to determine the kind of feeds that can be 
generated, and the methods that are available in the `setUp()` method.

|                | **Base Class**                                   |
|----------------|--------------------------------------------------|
| **RSS Only**   | `LaravelSyndication\Feeds\RssFeed::class`        |
| **RSS & Atom** | `LaravelSyndication\Feeds\RssAndAtomFeed::class` |
| **Atom Only**  | `LaravelSyndication\Feeds\AtomFeed::class`       |

#### Configuration methods

All configuration on a feed object is done in the `setUp()` method.

For example:

```php
namespace App\Feeds;

class Podcast extends RssAndAtomFeed
{
  public function setUp(): void
  {
    $this->model(\App\Models\Podcast::class)
      ->title("Awesome Podcast")
      ->description("An amazing podcast.")
      ->url(url('/podcasts'))
      ->language("en")
      ->updated($this->lastCachedAt());
  }
}
```


### Configuring the feed model

Once you have created your feed object and specified the model and filter you
would like to use when generating feed contents. You will need to add the
`LaravelSyndication\Contracts\Models\IsSyndicationItem` interface to your model.

The `IsSyndicationItem` interface specifies a single method `toFeedItem` that
is expected to return an instance of `LaravelSyndication\Feeds\FeedItem`.

For example:

```php
function toFeedItem(): FeedItem
{
    return (new FeedItem())
        ->title($this->title)
        ->description($this->excerpt)
        ->url(route('postcast.listen', $this->slug));
}
```

You can also create the feed item using an associative array if you prefer, by
passing it as an argument to the FeedItem construct. For example:

```php
function toFeedItem(): FeedItem
{
    return new FeedItem([
        'title' => $this->title,
        'description' => $this->excerpt,
        'url' => route('podcast.listen', $this->slug)    
    ]);
}
```

Additional options are also available when creating your FeedItem:

```php
/**
 * Will specify the URL for comments.  
 */
FeedItem::comments(string $commentsUrl)
```

```php    
/**
 * The email address of the feed item author.
 *
 * Can be specified as an array or Collection of \LaravelSyndication\Feeds\Structure\Atom\Person objects,
 * or a single LaravelSyndication\Feeds\Structure\Atom\Person object.
 */
FeedItem::author(array|Collection|Person $author)
```

```php
/**
 * Includes a media file to be included with an item.
 * 
 * The enclosure method accepts either 2, or 4 arguments, depending on what data you pass:
 * 
 * @param string      $url      The public URL to the enclosure item.
 * @param int|null    $length   Filesize in bytes -- if omitted, then an attempt to read the file is made.
 * @param string|null $type     MIME type of the enclosure -- required if $filename is null.
 * @param string|null $filename Optional, can be left blank if $length and $type are specified.
 */
FeedItem::enclosure(string $url, ?string $type = null, ?string $filename = null, ?int $length = null)
```

```php
/**
 * Specifies the value of the <id> tag on an <entry>.
 * 
 * If a Uuid instance of string is passed, then it is prepended with 'urn:uuid:'
 * before being output. Otherwise, it is output as it is passed.
 * 
 * This method can be omitted, and the value of the url() method will be used. 
 * 
 * Atom only. 
 */
FeedItem::id(int|\Ramsey\Uuid\Uuid|string $idUri)
```

```php
/**
 * Specifies the <content> tag of the <entry>.
 * 
 * Atom only. 
 */
FeedItem::content(null|string|\LaravelSyndication\Feeds\Structure\Atom\Content $content)
```

```php
/**
 * When specified, the value of FeedItem::content() is ignored and content is 
 * generated from a passed enclosure instead.
 * 
 * Note: If used, this should be called after FeedItem::enclosure().
 * 
 * Atom only. 
 */
FeedItem::contentFromEnclosure()
```

```php
/**
 * Sets the <updated> tag on an entry.
 *
 * Atom only. 
 */
FeedItem::updated(\Carbon\Carbon $updated)
```

```php
/**
 * Atom: Sets the <published> tag on an entry.
 * RSS: Sets the <pubDate> tag on an item. 
 */
FeedItem::published(\Carbon\Carbon $published)
```

```php
/**
 * Sets the <rights> attribute on an entry.
 * 
 * Atom only. 
 */
FeedItem::copyright(string $copyright)
```

```php
/**
 * Sets the value of the <category> tag on an <entry>.
 *
 * Atom only. 
 */
FeedItem::category(string $term)
```

```php
/**
 * Used to specify the <source> tag of an <entry>, for example if the <entry>
 * is a copy, or references another source. 
 */
FeedItem::source(\LaravelSyndication\Feeds\Structure\Items\Atom\Source $source)
```

Below is an example of how you might configure a source:

```php
FeedItem::source(
  new Source(
    id: route('podcast.listen', $this->getKey()),
    title: $this->title,
    updated: $this->updated_at,
    author: new Person(name: $this->author->name, email: $this->author->email)
  )
)
```

### Fully configured model example

```php
function toFeedItem(): FeedItem
{
  return (new FeedItem())
    // Using the ID method assumes that the model is making use of UUID
    // primary keys.
    ->id($this->getKey()) 
    
    // These are the common fields between Atom and RSS fields.
    ->title($this->seo_title ?? $this->title)
    ->description($this->meta_description ?? $this->excerpt ?? $this->title)
    ->url(route('podcast.listen', $this->getKey()))
    
    // The URL for comments.
    // This is only used in RSS feeds and is not output as part of Atom.
    ->comments(route('podcast.single', $this->slug . '#comments'))
    
    // Atom feeds will output specific <author> tags, whereas RSS
    // will output a comma-separated list of email addresses.
    ->author([
        new Person(name: $this->authorId->name, email: $this->authorId->email),
        new Person(name: $this->authorId->name, email: $this->authorId->email),
    ])
    
    // Specifies the data to be used in the <enclosure> tag in an RSS feed.
    // can be used in conjunction with FeedItem::contentFromEnclosure() to 
    // create the appropriate <content> tag on an Atom feed.
    ->enclosure(
        url: route('post.read', $this->slug), 
        filename: storage_path('podcasts/' . $this->podcast_file_location)
    )
    
    // ... Like this.
    ->contentFromEnclosure()
    
    // Sets the value of the <updated> tag. Only used as part of Atom feeds.
    ->updated($this->updated_at)
    
    // Sets the value of the <published> or <pubDate> tag in Atom and RSS
    // feeds respectively.
    ->published($this->published_at)
    
    // Copyright information relating specifically to an entry on an Atom feed
    // <entry> item.
    ->copyright("Copyright 2022 Acme Inc.")
    
    // Copyright information relating specifically to an entry on an Atom feed
    // <entry> item.
    ->category("Tech")
    
    // Builds the value of the <source> tag.
    // Statically typed here -- but you get the idea of what it does.
    ->source(
        new Source(
            id: 'https://example.com/some-unqiue-url-that-wont-change',
            title: 'The Title of The Source',
            updated: now(),
            author: new Person(name: "John Doe", email: "john@example.com", uri: "https://example.com/authors/john")
        )
    );
}
```

---

### Additional `Feed` configuration options

#### Adding copyright information

Add the `LaravelSyndication\Contracts\Feeds\SyndicatesWithCopyright` interface,
you will then have to declare the following method:

```php
function copyright(): ?string;
```

#### Adding category information

Add the `LaravelSyndication\Contracts\Feeds\SyndicatesWithCategory` interface,
you will then have to declare the following method:

```php
function category(): ?string;
```

#### Adding image information

Add the `LaravelSyndication\Contracts\Feeds\SyndicatesWithImage` interface,
you will then have to declare the following methods:

```php
function image(): \LaravelSyndication\Feeds\Structure\FeedImage;
```

#### Adding cloud data

Specify the `cloud` method on your `Feed` object, and return an instance
of `\LaravelSyndication\Feeds\Structure\FeedCloud`, for example:

```php
function cloud(): \LaravelSyndication\Feeds\Structure\FeedCloud
{
    return (new FeedCloud)
        ->domain('example.com')
        ->path('/rpc')
        ->port(443)
        ->procedure('NotifyMe')
        ->protocol('xml-rpc');
}
```

#### Adding a generator

The generator can be used to specify the application used to generate the feed,
and can be expressed either using the `generator` property or `generator()` method.

```php
public $generator = "My Feed Generator v1.0";
```

```php
function generator()
{
    return sprintf("My Feed Generator v%s", app()->version());
}
```

#### Adding a TTL

Specifies the number of minutes the feed can stay cached before refreshing it from the source,
and can be expressed either using the `ttl` property or `ttl()` method.

```php
public $ttl = 1440;
```

```php
function ttl()
{
    return 24*60;
}
```

# Outputting meta tags to view

Add the following code to your blade views to output meta tags for registered
feeds.

These use the alias of the facade at: `LaravelSyndication\Facades\Syndicate`

To meta tags for all registered feeds:

```blade
<head>
{!! LaravelSyndication::meta() !!}
</head>
```

To output meta tags for specific feeds:

```blade
<head>
{!! LaravelSyndication::meta('podcasts', 'blog') !!}
</head>
```
