<?php

namespace LaravelSyndication\Feeds;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LaravelSyndication\Contracts\Models\IsSyndicationItem;
use LaravelSyndication\Feeds\Concerns;

abstract class Feed
{
    use Concerns\UsesCache,
        Concerns\UsesTitle,
        Concerns\UsesDescription,
        Concerns\UsesUrl,
        Concerns\UsesCategories,
        Concerns\UsesGenerator,
        Concerns\UsesCopyright,
        Concerns\UsesTimestamps;

    protected ?string $identifier = null;
    protected ?string $requestedFeedType = null;

    /**
     * The name of the model class.
     *
     * @var string|null
     */
    public ?string $model = null;

    /**
     * Sets up an instance of the current feed object.
     */
    abstract function setUp(): void;

    /**
     * Get the model used in this feed.
     *
     * @return $this
     */
    public function model(string $model): static
    {
        $this->model = $model;
        return $this;
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
        if (!empty($this->identifier)) {
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
        if (!empty($this->requestedFeedType)) {
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
        $model = app()->make($this->model);
        $items = $this->filter(
            $model->newQuery()
        )->get();

        return $items->map(function (Model $model) {
            if (!$model instanceof IsSyndicationItem) {
                throw new \Exception(sprintf("Model [%s] must be an instance of [%s].", get_class($model), IsSyndicationItem::class));
            }

            return $model->toFeedItem();
        });
    }

    /**
     * Get the feed data.
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getData(): array
    {
        return [
            'encoding' => config('syndication.encoding', 'utf-8'),
            'feed' => $this,
            'items' => $this->getItems()
        ];
    }
}
