<?php

namespace App\MyStuff\Storage;

use App\MyStuff\Storage\File;

class FileStorage extends File 
{
    public string $defaultExtension = '';

    public function __construct(array $config) 
    {
        parent::__construct($config);
                
    }

    public function create() : mixed
    {
        $this->setExtension();
        $this->setName();
        
        $this->setAccessors();
        $this->save();

        return $this;
    }

    public function setExtension()
    {
        if(method_exists($this->file, 'extension')){
            if($this->extension = $this->file->extension()){
                return;
            }
        }

        if($this->file?->name){
            $temp = explode('.', $this->file->name);

            if(isset($temp[1])) {
                $this->extension = $temp[1];
                return;
            }
        }

        $this->extension = '';
    }

    protected function save() : bool
    {
        self::incCounter();
        return $this->disk->put($this->path, file_get_contents($this->file));
    }
}