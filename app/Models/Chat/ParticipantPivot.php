<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ParticipantPivot extends Pivot
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'user_id', 'group_id', 'last_message_seen_id', 'participant_role', 'updated_at', 'accepted', 'invited_by_user_id'
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];


    const SELF_RETRIEVABLE_FILEDS = [
        'id', 'user_id', 'group_id', 'last_message_seen_id', 'participant_role', 'updated_at', 'accepted', 'invited_by_user_id'
    ];

    const USER_RETRIEVABLE_FILEDS = [
        'users.id', 'users.first_name', 'users.last_name', 'users.thumbnail'
    ];

    protected $table = 'group_participants';

}
