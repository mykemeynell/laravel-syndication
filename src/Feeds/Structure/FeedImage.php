<?php

namespace LaravelSyndication\Feeds\Structure;

use Illuminate\Contracts\Support\Renderable;

class FeedImage
{
    /**
     * URL of the image.
     *
     * @see https://www.w3schools.com/xml/rss_tag_image.asp
     * @var string
     */
    protected string $url;

    /**
     * Title of the image.
     *
     * @see https://www.w3schools.com/xml/rss_tag_image.asp
     * @var string
     */
    protected string $title;

    /**
     * Link that is relevant to the image.
     *
     * @see https://www.w3schools.com/xml/rss_tag_image.asp
     * @var string
     */
    protected string $link;

    /**
     * Set the FeedImage using an associative array.
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
     * Set the url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function url(string $url): FeedImage
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Set the title of the image.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title(string $title): FeedImage
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the link of the image.
     *
     * @param string $link
     *
     * @return $this
     */
    public function link(string $link): FeedImage
    {
        $this->link = $link;
        return $this;
    }

    private function view(): Renderable
    {
        return view('syndication::tags.rss.image')
            ->with('image', $this);
    }

    public function __toString(): string
    {
        return $this->view();
    }

    public function __get(string $name)
    {
        if(!property_exists($this, $name)) {
            throw new \Exception(sprintf("Unknown property [%s] on [%s]", $name, $this::class));
        }

        return $this->{$name};
    }
}
