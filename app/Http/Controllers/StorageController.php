<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\MyStuff\Storage\Zip;
use App\MyStuff\Storage\FilePathFromUrl;

class StorageController extends Controller
{
    public function getFile(Request $request, $path)
    {
        return Storage::disk('ChatMessage')->response($path);
    }


    // public function chatImages(Request $request)
    // {
    //     $zip = new Zip('ChatMessage');

    //     $paths = (new FilePathFromUrl($request->urls))->get();

    //     $zip->create($paths);

    //     return response()->download($zip->zipFileStoragePath);
    // }
}
