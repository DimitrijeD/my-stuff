<?php

namespace App\MyStuff\Repos\File;

use App\MyStuff\Repos\File\Contracts\FileRepo;
use App\MyStuff\General\Traits\CRUDTrait;
use App\Models\File;
use App\MyStuff\Storage\FileStorage;

class FileEloquentRepo implements FileRepo
{
    use CRUDTrait;

    public function getModel()
    {
        return File::class;
    }

    public function setMessageFiles($message, $files = [])
    {
        $filesURLs = [];
        $fileConfigPath = 'uploadedFiles.ChatMessage';

        if($files){
            foreach($files as $file){
                $filesURLs[] = (new FileStorage(config($fileConfigPath)))
                    ->file($file)
                    ->create()
                    ->url();
            }
        }

        if($filesURLs){
            $props = [
                'parent_id' => $message->id,
                'parent_model' => $message::class,
                'config_path' => $fileConfigPath,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->createMany(
                array_map(function($url) use ($props){
                    return array_merge($props, ['url' => $url]);
                }, $filesURLs)
            );

            $message->files;
        } else {
            $message->files = [];
        }

        return $message;
    }
}
