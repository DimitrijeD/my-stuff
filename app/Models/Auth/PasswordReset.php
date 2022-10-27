<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PasswordReset extends Model
{
    use HasFactory;

    const EMAIL_HASH_LENGTH = 64;
    const MAX_REQUESTS = 10;
    
    const SUCCESS_KEY = 'success';
    const FAKE_SUCCESS_KEY = 'success';
    const UPDATE_KEY = 'update';
    const MAX_REQUESTS_EXCEEDED_KEY = 'requests_exceeded';
    
    protected $table = 'password_resets';

    protected $fillable = ['attempts', 'email', 'token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
