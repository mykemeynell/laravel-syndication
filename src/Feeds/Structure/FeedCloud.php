<?php

namespace LaravelSyndication\Feeds\Structure;

use Illuminate\Contracts\Support\Renderable;

class FeedCloud
{

    /**
     * @see https://www.w3schools.com/xml/rss_tag_cloud.asp
     * @var string
     */
    protected string $domain;

    /**
     * @see https://www.w3schools.com/xml/rss_tag_cloud.asp
     * @var int
     */
    protected int $port;

    /**
     * @see https://www.w3schools.com/xml/rss_tag_cloud.asp
     * @var string
     */
    protected string $path;

    /**
     * @see https://www.w3schools.com/xml/rss_tag_cloud.asp
     * @var string
     */
    protected string $procedure;

    /**
     * @see https://www.w3schools.com/xml/rss_tag_cloud.asp
     * @var string
     */
    protected string $protocol;

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
     * @param string $domain
     *
     * @return FeedCloud
     */
    public function domain(string $domain): FeedCloud
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @param int $port
     *
     * @return FeedCloud
     */
    public function port(int $port): FeedCloud
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param string $path
     *
     * @return FeedCloud
     */
    public function path(string $path): FeedCloud
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param string $procedure
     *
     * @return FeedCloud
     */
    public function procedure(string $procedure): FeedCloud
    {
        $this->procedure = $procedure;
        return $this;
    }

    /**
     * @param string $protocol
     *
     * @return FeedCloud
     */
    public function protocol(string $protocol): FeedCloud
    {
        $this->protocol = $protocol;
        return $this;
    }

    private function view(): Renderable
    {
        return view('syndication::tags.rss.cloud')
            ->with('cloud', $this);
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
