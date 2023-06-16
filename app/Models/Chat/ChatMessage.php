<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\File;

class ChatMessage extends Model
{
    use HasFactory;

    const INIT_NUM_MESSAGES = 20;
    const EARLIEST_NUM_MESSAGES = 20;

    protected $fillable = [
        'group_id', 'user_id', 'text', 'updated_at', 'edited'
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(ChatGroup::class, 'group_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable', 'parent_model', 'parent_id');
    }
}
