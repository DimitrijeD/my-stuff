<?php

namespace App\MyStuff\Storage; 

class FilePathFromUrl 
{
    public $urls, $paths;

    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->paths = [];
    }

    public function get()
    {
        foreach($this->urls as $url){
            $this->paths[] = $this->trim_path($url);
        }

        return $this->paths;
    }

    private function trim_path($url) {
        $parts = explode('/', parse_url($url)['path']);
        
        return empty($parts[0])
            ? implode('/', array_slice($parts, 2))
            : implode('/', array_slice($parts, 1));
    }

}