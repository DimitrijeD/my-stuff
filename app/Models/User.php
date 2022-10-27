<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Auth\PasswordReset;

class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'image',
        'thumbnail',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'account_verification',
        'password_reset'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function account_verification()
    {
        return $this->hasOne(AccountVerification::class);
    }

    /**
     * All User's chat groups
     */
    public function groups()
    {
        return $this->belongsToMany(ChatGroup::class, 'group_participants', 'user_id', 'group_id')
            ->withPivot(['last_message_seen_id', 'group_id', 'updated_at']);
    }

    public function password_resets()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }
}
