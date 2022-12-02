<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatGroup extends Model
{
    use HasFactory;
    
    const TYPE_PRIVATE = 'PRIVATE';
    const TYPE_PROTECTED = 'PROTECTED';
    const TYPE_PUBLIC_OPEN = 'PUBLIC_OPEN';
    const TYPE_PUBLIC_CLOSED = 'PUBLIC_CLOSED';

    const TYPES = [
        self::TYPE_PRIVATE,
        self::TYPE_PROTECTED,
        self::TYPE_PUBLIC_OPEN,
        self::TYPE_PUBLIC_CLOSED,
    ];

    const TYPE_DEFAULT = 'PROTECTED';

    protected $fillable = [
        'name', 'model_type', 'updated_at', 'last_msg_id'
    ];

    protected $table = 'chat_groups';
    
    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'group_id', 'id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, ParticipantPivot::class, 'group_id', 'user_id')
            ->withPivot(ParticipantPivot::SELF_RETRIEVABLE_FILEDS)
            ->select(ParticipantPivot::USER_RETRIEVABLE_FILEDS);
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'group_id', 'id')
            ->latestOfMany();
    }

    public function latestMessages()
    {
        return $this->hasMany(ChatMessage::class, 'group_id', 'id')
            ->limit(ChatMessage::INIT_NUM_MESSAGES);
    }
}
