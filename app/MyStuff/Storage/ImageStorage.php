<?php

namespace App\MyStuff\Storage;

use App\MyStuff\Storage\File;
use Image;

class ImageStorage extends File
{
    public int $maxWidth; 
    public bool $shouldReduceSize;
    public string $defaultImagePath; 
    public string $defaultExtension = 'jpg';

    public function __construct(array $config) 
    {
        parent::__construct($config);
                
        $this->maxWidth = $config['maxWidth'] ?? 0;
        $this->shouldReduceSize = $this->maxWidth 
            ? true 
            : false;

        $this->defaultImagePath = $config['default'] ?? ''; 
    }

    public function image(mixed $file)
    {
        $this->file = $file;
        return $this;
    }

    public function getWidth() : int
    {
        if($this->maxWidth == 0) return $this->file->width();

        return $this->file->width() >= $this->maxWidth
            ? $this->maxWidth
            : $this->file->width();
    }

    public function create() : mixed
    {
        $this->resolvePath();
        $this->makeImage();
        $this->setExtension();
        $this->setName();
        
        $this->resize();
        $this->setAccessors();
        $this->save();

        return $this;
    }

    public function setExtension()
    {
        if($this->file?->extension){
            $this->extension = $this->file->extension;
            return;
        }

        if(method_exists($this->file, 'extension')){
            if($this->file->extension()){
                $this->extension = $this->file->extension();
                return;
            }
        }

        if($this->file?->filename){
            $temp = explode('.', $this->file->filename);
            
            if(isset($temp[1])) {
                $this->extension = $temp[1];
                return;
            }
        }

        $this->extension = $this->defaultExtension;
    }

    private function resolvePath() : void
    {
        $usesDefault = !$this->file 
            ? true 
            : false;

        $this->filePath = $usesDefault 
            ? $this->defaultImagePath
            : $this->file->path();
    }

    /**
     * Create object from image file
     *
     * @return \Image
     */
    private function makeImage() : void
    {
        $this->file = Image::make($this->filePath);
    } 

    private function resize()
    {
        if(! $this->shouldReduceSize) return;

        $this->file = $this->file->resize($this->getWidth(), null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode($this->extension, 100);
    }

    protected function save() : bool
    {
        self::incCounter();
        return $this->disk->put($this->path, $this->file);
    }

}
