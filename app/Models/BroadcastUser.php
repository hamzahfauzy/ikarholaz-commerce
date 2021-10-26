<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function broadcast()
    {
        return $this->belongsTo('App\Broadcast');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
