<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
    ];
}
