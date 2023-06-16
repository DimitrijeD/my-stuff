<?php

namespace App\MyStuff\Storage;

use \Illuminate\Support\Facades\Storage;

abstract class File 
{
    static $counter = 0;
    public string $url; 
    public string $name; 
    public string $extension;
    public string $directory;
    public string $path;
    public string $filePath;

    protected mixed $disk;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->disk = Storage::disk($this->config['disk'] ?? 'public');
        $this->directory = $config['directory'];
        $this->url  = '';
        $this->name = '';
        $this->path = '';
    }

    /**
     * Resizes image if necessary and stores it in path provided by $config
     *
     * @return string $url
     */
    abstract public function create() : mixed;

    abstract public function setExtension();

    abstract protected function save() : bool;

    public function file(mixed $file) : mixed
    {
        $this->file = $file;
        return $this;
    }

    public function getPathFromUrl(string $url)
    {
        $split = explode('/', $url);
        return $this->directory . '/' . $split[count($split) - 1];
    }

    public function delete(string $url) : bool
    {
        return $this->disk->delete($this->getPathFromUrl($url));
    }

    protected function setName() : void
    {
        $this->name = time() . '_' . self::$counter;

        if($this->extension)
            $this->name .= '.' . $this->extension; 
    }

    protected function setAccessors() : void
    {
        $this->setPath();
        $this->setUrl();
    }

    protected function setUrl() : void
    {
        $this->url = $this->getBaseURL() . '/' . $this->path;
    }

    public function url() : string
    {
        return $this->url;
    }

    public function getBaseURL() : string
    {
        return config("filesystems.disks.{$this->config['disk']}.url");
    }

    public function getPath() : string
    {
        return $this->path;
    }

    protected function setPath() : void
    {
        $this->path = $this->directory . '/' . $this->name;
    }

    protected function incCounter()
    {
        self::$counter++;
    }
}