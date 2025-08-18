<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity', 'entity_id', 'event_type', 'origin', 'gateway', 'url', 'raw', 'type', 'status', 'attempts', 'last_attempt', 'response_status', 'response_raw',
    ];

   
}
