<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $guarded = [];

    static $rules = [
        'name' => 'required',
        'graduation_year' => 'required',
        'gender' => 'required',
        'address' => 'required',
        'city' => 'required',
        'province' => 'required',
        'country' => 'required',
        'date_of_birth' => 'required',
    ];

    protected $appends = ['tanggal'];

    function getTanggalAttribute()
    {
        $tanggal = date('Y-m-d',strtotime($this->created_at));
        return $tanggal;
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function skills()
    {
        return $this->hasMany(Skill::class);
    }
}
