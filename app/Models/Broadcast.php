<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Broadcast
 *
 * @property $id
 * @property $title
 * @property $message
 * @property $url
 * @property $created_at
 * @property $updated_at
 *
 * @property BroadcastUser[] $broadcastUsers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Broadcast extends Model
{
    
    static $rules = [
		'title' => 'required',
		'message' => 'required',
		'url' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title','message','url'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function broadcastUser()
    {
        return $this->hasMany('App\Models\BroadcastUser', 'broadcast_id', 'id');
    }
    

}
