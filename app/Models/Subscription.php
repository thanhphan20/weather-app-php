<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'user_id',
        'status',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
