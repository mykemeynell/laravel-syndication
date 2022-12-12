<?php

namespace LaravelSyndication\Contracts\Feeds;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelSyndication\Feeds\Structure\Atom\Person;

/**
 * @method Person author()  Names one author of the feed. A feed may have multiple author elements.
 *                          A feed must contain at least one author element unless
 *                          all the entry elements contain at least one
 *                          author element.
 *                          If the returned array is empty, then the tag is omitted from the feed.
 *                          @see https://validator.w3.org/feed/docs/atom.html#recommendedFeedElements
 *                          @see https://validator.w3.org/feed/docs/atom.html#person
 * @method Collection<Person>|array<Person> contributors() Should return an array or Collection of person objects.
 * @method string subtitle() Contains a human-readable description or subtitle for the feed. If the method is left
 *                           unspecified and there is no $subtitle property, then description() is used instead.
 *                           @see https://validator.w3.org/feed/docs/atom.html#optionalFeedElements
 *                           @see https://validator.w3.org/feed/docs/atom.html#text
 */
interface AtomFeed
{
    /**
     * Identifies the feed using a universally unique and permanent URI. If you
     * have a long-term, renewable lease on your Internet domain name,
     * then you can feel free to use your website's address.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#requiredFeedElements
     * @return string
     */
    function id(): string;

    /**
     * Indicates the last time the entry was modified in a significant way.
     * This value need not change after a typo is fixed, only after a
     * substantial modification. Generally, different entries in
     * a feed will have different updated timestamps.
     *
     * @see https://validator.w3.org/feed/docs/atom.html#requiredFeedElements
     * @return Carbon
     */
    function updated(): Carbon;
}
