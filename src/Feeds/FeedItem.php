<?php

namespace LaravelSyndication\Feeds;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelSyndication\Contracts\Feeds\AtomFeed;
use LaravelSyndication\Feeds\Concerns\UsesAuthor;
use LaravelSyndication\Feeds\Concerns\UsesCategories;
use LaravelSyndication\Feeds\Concerns\UsesComments;
use LaravelSyndication\Feeds\Concerns\UsesContent;
use LaravelSyndication\Feeds\Concerns\UsesCopyright;
use LaravelSyndication\Feeds\Concerns\UsesId;
use LaravelSyndication\Feeds\Concerns\UsesSources;
use LaravelSyndication\Feeds\Concerns\UsesTimestamps;
use LaravelSyndication\Feeds\Structure\Atom\Person;
use LaravelSyndication\Feeds\Structure\Items\Atom\Source;
use LaravelSyndication\Feeds\Structure\Items\RSS\Enclosure;
use Ramsey\Uuid\Uuid;

/**
 * @method string getTitle()
 * @method string getUrl()
 * @method string getDescription()
 * @method null|string getAuthor()
 * @method null|string getComments()
 * @method null|Enclosure getEnclosure()
 * @method Carbon getUpdated()
 * @method null|Source getSource()
 * @method null|string getContent()
 * @method null|string getCopyright()
 * @method null|string getCategory()
 * @method null|Carbon getPublished()
 *
 * @method bool hasAuthor()
 * @method bool hasComments()
 * @method bool hasUpdated()
 * @method bool hasPublished()
 * @method bool hasCategory()
 * @method bool hasSource()
 * @method bool hasContent()
 * @method bool hasCopyright()
 */
class FeedItem
{
    use UsesCategories, UsesCopyright, UsesId, UsesContent, UsesComments, UsesAuthor, UsesTimestamps, UsesSources;

    /**
     * Title of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_title_link_description_item.asp
     * @var string
     */
    protected string $title;

    /**
     * URL of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_title_link_description_item.asp
     * @var string
     */
    protected string $url;

    /**
     * Description of the feed item.
     *
     * @see https://www.w3schools.com/xml/rss_tag_title_link_description_item.asp
     * @var string
     */
    protected string $description;

    /**
     * Set the FeedItem using an associative array.
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        foreach($config as $property => $value) {
            if(!property_exists($this, $property)) {
                throw new \Exception(sprintf("Attempting to set unknown property [%s] on [%s]", $property, self::class));
            }

            $this->{$property} = $value;
        }
    }

    /**
     * Set the title of the feed item.
     *
     * @param string $title
     *
     * @return FeedItem
     */
    public function title(string $title): FeedItem
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the URL of the feed item.
     *
     * @param string $url
     *
     * @return FeedItem
     */
    public function url(string $url): FeedItem
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the description of the feed item.
     *
     * @param string $description
     *
     * @return FeedItem
     */
    public function description(string $description): FeedItem
    {
        $this->description = $description;
        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        if (str_starts_with($name, 'get') && !method_exists($this, $name)) {
            $property = Str::camel(Str::after($name, 'get'));

            if (!property_exists($this, $property)) {
                throw new \Exception(sprintf("Unknown property [%s] on [%s] when using magic getter [%s]", $property, $this::class, $name));
            }

            return $this->{$property};
        }

        if(str_starts_with($name, 'has') && !method_exists($this, $name)) {
            $property = Str::camel(Str::after($name, 'has'));

            if (!property_exists($this, $property)) {
                throw new \Exception(sprintf("Unknown property [%s] on [%s] when using magic getter [%s]", $property, $this::class, $name));
            }

            return !empty($this->{$property});
        }

        return call_user_func_array([$this, $name], $arguments);
    }
}
