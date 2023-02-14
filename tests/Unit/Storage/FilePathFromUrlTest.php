<?php

namespace Tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use App\MyStuff\Storage\FilePathFromUrl;

class FilePathFromUrlTest extends TestCase
{

    public function test_ok()
    {
        $asd = (new FilePathFromUrl([
            'http://localhost/c/ChatGroup/ChatMessage/1673275348_0.png',
            'http://localhost/c/ChatGroup/ChatMessage/1673274521_0.webp',
        ]))->get();

        dd($asd);
    }
}
