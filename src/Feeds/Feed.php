<?php

namespace LaravelSyndication\Feeds;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use LaravelSyndication\Contracts\Feeds\SyndicatesWithCategory;
use LaravelSyndication\Contracts\Feeds\SyndicatesWithCopyright;
use LaravelSyndication\Contracts\Feeds\SyndicatesWithImage;
use LaravelSyndication\Contracts\Models\IsSyndicationItem;
use LaravelSyndication\Feeds\Concerns\UsesCache;
use Ramsey\Uuid\Uuid;

abstract class Feed
{
    use UsesCache;

    protected ?string $identifier = null;
    protected ?string $requestedFeedType = null;

    /**
     * Get the model used in this feed.
     *
     * @return string
     */
    abstract function model(): string;

    /**
     * Title for the feed.
     *
     * @return string
     */
    abstract function title(): string;

    /**
     * Description of the feed.
     *
     * @return string
     */
    abstract function description(): string;

    /**
     * URL relevant to the feed.
     *
     * @return string
     */
    abstract function url(): string;

    /**
     * Test if this feed has category data.
     *
     * @return bool
     */
    function hasCategory(): bool
    {
        return $this instanceof SyndicatesWithCategory
            && !empty($this->category());
    }

    /**
     * Test if the feed has the copyright attribute.
     *
     * @return bool
     */
    function hasCopyright(): bool
    {
        return $this instanceof SyndicatesWithCopyright
            && !empty($this->copyright());
    }

    /**
     * Test if the feed has the image attribute.
     *
     * @return bool
     */
    function hasImage(): bool
    {
        return $this instanceof SyndicatesWithImage
            && !empty($this->image());
    }

    /**
     * Test if the feed has the cloud attribute.
     *
     * @return bool
     */
    function hasCloud(): bool
    {
        return method_exists($this, 'cloud');
    }

    /**
     * Identifies a small image which provides iconic visual identification for
     * the feed. Icons should be square.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     * @return string|null
     */
    function icon(): ?string
    {
        if(property_exists($this, 'icon')) {
            return $this->icon;
        }

        return null;
    }

    /**
     * Identifies a larger image which provides visual identification for the
     * feed. Images should be twice as wide as they are tall.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     * @return string|null
     */
    function logo(): ?string
    {
        if(property_exists($this, 'logo')) {
            return $this->logo;
        }

        return null;
    }

    /**
     * Test if the feed has contributors.
     *
     * @return bool
     */
    function hasContributors(): bool
    {
        if(!method_exists($this, 'contributors')) {
            return false;
        }

        return !empty($this->contributors());
    }

    /**
     * Contains a human-readable description or subtitle for the feed.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
     * @see https://validator.w3.org/feed/docs/atom.html#text
     * @return string
     */
    function subtitle(): string
    {
        if(property_exists($this, 'subtitle')) {
            return $this->subtitle;
        }

        return $this->description();
    }

    /**
     * Specifies the amount of time in minutes that the feed is valid for
     * before an application should make another request to the feed
     * source.
     *
     * @return null
     */
    function ttl()
    {
        if(property_exists($this, 'ttl')) {
            return $this->ttl;
        }

        return null;
    }

    /**
     * Specifies the program used to generate the feed.
     *
     * @return null
     */
    function generator()
    {
        if(property_exists($this, 'generator')) {
            return $this->generator;
        }

        return null;
    }

    /**
     * Return a builder that is used to filter the items that are loaded into the feed.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    function filter(Builder $builder): Builder
    {
        return $builder;
    }

    /**
     * Identifies a related Web page. The type of relation is defined by the
     * rel attribute. A feed is limited to one alternate per
     * type and hreflang. A feed should contain a
     * link back to the feed itself.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#recommendedFeedElements
     * @see https://validator.w3.org/feed/docs/atom.html#link
     * @return string
     */
    function atomSelfLink(): string
    {
        return route('syndication', $this->identifier . '.atom');
    }

    /**
     * Gets the Atom feed ID. If the ID is of type UUID or a string that matches
     * the UUID format, then urn:uuid: is prepended.
     *
     * @return string
     * @throws \Exception
     */
    function getAtomId(): string
    {
        if(!method_exists($this, 'id')) {
            throw new \Exception(sprintf("ID is a required attribute for Atom feeds."));
        }

        if($this->id() instanceof Uuid || Str::isUuid($this->id())) {
            return sprintf("urn:uuid:%s", $this->id());
        }

        return $this->id();
    }

    /**
     * This is used internally when generating feeds and self-references,
     * for example: when using Atom and creating the "self" link.
     *
     * @param string $identifier
     *
     * @return bool
     * @throws \Exception
     */
    function identifier(string $identifier): bool
    {
        if(!empty($this->identifier)) {
            throw new \Exception("A Feed can only have its identifier set once.");
        }

        $this->identifier = $identifier;
        return true;
    }

    /**
     * The requested feed type for a feed object.
     *
     * @param string $requestedFeedType
     *
     * @return bool
     * @throws \Exception
     */
    function requestedFeedType(string $requestedFeedType): bool
    {
        if(!empty($this->requestedFeedType)) {
            throw new \Exception("A Feed can only have its feed type set once.");
        }

        $this->requestedFeedType = $requestedFeedType;
        return true;
    }

    /**
     * Get the items to be output in this feed.
     *
     * @return Collection
     * @throws BindingResolutionException
     */
    function getItems(): Collection
    {
        /** @var Model $model */
        $model = app()->make($this->model());
        $items = $this->filter(
            $model->newQuery()
        )->get();

        return $items->map(function (Model $model) {
            if(!$model instanceof IsSyndicationItem) {
                throw new \Exception(sprintf("Model [%s] must be an instance of [%s].", get_class($model), IsSyndicationItem::class));
            }

            return $model->toFeedItem();
        });
    }
}
