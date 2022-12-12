<?php

namespace LaravelSyndication\Feeds\Structure\Items\RSS;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Enclosure
{
    protected string $url;
    protected int $length;
    protected string $type;
    protected ?string $content = null;
    protected ?string $filename = null;

    /**
     * @param string      $url      The public URL to the enclosure item.
     * @param int|null    $length   Filesize in bytes -- required if $filename is null.
     * @param string|null $type     MIME type of the enclosure -- required if $filename is null.
     * @param string|null $filename Optional, can be left blank if $length and $type are specified.
     *
     * @throws \Exception
     */
    public function __construct(string $url, ?string $type = null, ?string $filename = null, ?int $length = null)
    {
        $this->url = $url;
        $this->filename = $filename;

        if (!$filename && !$type) {
            throw new \Exception("Either a type or a filename must be given so these can be extracted.");
        }

        $this->type = !$type ? File::mimeType($filename)
            : $type;

        $this->length = $length ?? $this->getContentLength();
    }

    public function url(): string
    {
        return $this->url;
    }

    public function length(): int
    {
        return $this->length;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function view(): Renderable
    {
        return view('syndication::tags.rss.enclosure')
            ->with('enclosure', $this);
    }

    /**
     * Get the contents of the enclosure.
     *
     * @return string|null
     */
    public function contents(): ?string
    {
        $contents = $this->getContents();

        if(in_array($this->type, ['text', 'html', 'xhtml'])) {
            return $contents;
        }

        if(str_ends_with($this->type, 'xml')) {
            return $contents;
        }

        if(str_starts_with($this->type, 'text')) {
            return addslashes($contents);
        }

        return sprintf("data:%s;base64,%s", $this->type, base64_encode($contents));
    }

    /**
     * Get the content length.
     *
     * @return int
     */
    private function getContentLength(): int
    {
        return strlen($this->getContents());
    }

    /**
     * Get the contents of the enclosure using the filesystem or cURL if
     * no filename is specified.
     *
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getContents(): ?string
    {
        if(!empty($this->filename)) {
            return File::get($this->filename);
        }


        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->url);

        return $response->getBody()->getContents();
    }

    public function __toString(): string
    {
        return $this->view();
    }
}
