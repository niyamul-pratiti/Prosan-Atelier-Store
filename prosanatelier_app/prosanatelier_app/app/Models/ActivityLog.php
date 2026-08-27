<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'admin_name',
        'action',
        'method',
        'route_name',
        'path',
        'description',
        'request_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'request_data' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
