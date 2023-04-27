<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $table = "social_accounts";
    protected $fillable = [
        "social_id",
        "social_name",
        "social_email",
        "social_avatar",
        "token",
        "refreshToken"
    ];

    protected $attributes = [

    ];

    protected $hidden = [

    ];

    protected $casts = [

    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
