<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    const TABLE = 'files';

    protected $fillable = [
        'parent_model', 'parent_id', 'url', 'config_path', 'created_at', 'updated_at' 
    ];

    protected $hidden = [
        'config_path'
    ];

    public $timestamps = true;

    public function fileable()
    {
        return $this->morphTo();
    }
}
