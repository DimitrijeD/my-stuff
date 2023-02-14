<?php

namespace App\MyStuff\Storage;

use ZipArchive;
use Illuminate\Support\Facades\Storage;

class Zip
{
    private $disk;
    public $zip, $diskName, $logs, $zipFileStoragePath;
    const ZIP_FILE_LOCATION = 'php://memory';

    public function __construct(string $diskName)
    {
        $this->diskName = $diskName;
        $this->disk = Storage::disk($this->diskName);
        $this->zip = new ZipArchive;

        $this->logs = [
            'num_files' => 0,
            'urls_failed_zipping' => [],
            'urls_dont_exist' => [],
            'zipped' => [], 
            'failed_opening' => false,
            'failed_closing' => false,
        ];

        $this->zipFileStoragePath = storage_path('app/public/images.zip');
    }

    public function create(array $paths)
    {
        if(! $this->openZip()) {
            $this->logFailedOpening();
            return $this;
        }

        foreach($paths as $path){
            
            if(! $this->disk->exists($path)) {
                $this->logFileOnPathDoesntExist($path);
                continue;
            }
            
            if(! $this->addFile( $path )){
                $this->logFileOnPathFailedZipping($path);
                continue;
            }

            $this->logFileZipped($path);
        }

        if(! $this->closeZip()){
            $this->logFailedClosing();
            return $this;
        }

        return $this->zip;
    }

    private function openZip() : bool
    {
        return $this->zip->open($this->zipFileStoragePath, $this->zip::CREATE | $this->zip::OVERWRITE);
    }

    private function closeZip() : bool
    {
        return $this->zip->close();
    }

    private function addFile(string $path)
    {
        return $this->zip->addFile($this->disk->path($path), basename($path));
    }
    
    // $zip->addFile(Storage::disk('ChatMessage')->path($path), basename($path));

    private function logFileOnPathDoesntExist($path)
    {
        $this->logs['urls_dont_exist'][] = $path;
    }

    private function logFileOnPathFailedZipping($path)
    {
        $this->logs['urls_failed_zipping'][] = $path;
    }

    private function logFileZipped($path)
    {
        $this->logs['zipped'][] = $path;
        $this->logs['num_files']++;
    }


    private function logFailedClosing()
    {
        $this->logs['failed_closing'] = true;
    }

    private function logFailedOpening()
    {
        $this->logs['failed_opening'] = true;
    }
    
}