<?php

namespace App\Models;

use App\MyStuff\Storage\ImageStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\PasswordReset;
use App\Models\Auth\AccountVerification;
use App\Models\Chat\ChatGroup;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'image',
        'thumbnail',
        'email_verified_at',
        'userSetting'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'account_verification',
        'password_reset'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function makeProfileImages($image)
    {
        $this->image = (new ImageStorage(config('images.user.image')))
            ->file($image)
            ->create()
            ->url();

        $this->thumbnail = (new ImageStorage(config('images.user.thumbnail')))
            ->file($image)
            ->create()
            ->url();
    }

    /**
     * Deletes image and thumbnail
     */
    public function deleteProfileImages()
    {
        (new ImageStorage(config('images.user.image'))    )->delete($this->image);
        (new ImageStorage(config('images.user.thumbnail')))->delete($this->thumbnail);
    }
    
    public function account_verification()
    {
        return $this->hasOne(AccountVerification::class);
    }

    public function userSetting()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * All User's chat groups
     */
    public function groups()
    {
        return $this->belongsToMany(ChatGroup::class, 'group_participants', 'user_id', 'group_id')
            ->withPivot(['last_message_seen_id', 'group_id', 'updated_at', 'accepted'])
            ->with(['participants', 'lastMessage.user'])
            ->orderBy('updated_at', 'desc');
    }

    public function password_resets()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }
}
