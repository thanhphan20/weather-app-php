<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Xác nhận đăng ký email.
     */
    public function confirmSubscription()
    {
        $this->update(['status' => 'confirmed', 'email_verified_at' => now()]);
    }

    /**
     * Kiểm tra xem email đã được xác nhận chưa.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }
}
