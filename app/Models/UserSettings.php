<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    const OPEN_ALL_CHATS_ON_NEW_MESSAGE = true;
    const SHOW_ONLY_IMPORTANT_NOTIFICATIONS = false;
    
    const LIGHT_THEME = 'light';
    const DARK_THEME = 'dark';

    const THEMES = [
        self::LIGHT_THEME,
        self::DARK_THEME
    ];
    const DEFAULT_THEME = self::DARK_THEME;

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'user_id';

    protected $fillable = [ 
        'theme', 
        'open_all_chats_on_new_message', 
        'show_only_important_notifications',  
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
