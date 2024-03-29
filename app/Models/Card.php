<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 *
 * @property $id
 * @property $card_number
 * @property $name
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Card extends Model
{
    
    static $rules = [
		'card_number' => 'required',
		'name' => 'required',
		'status' => 'required',
		'unique_number' => 'nullable',
		'tahun' => 'nullable',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['card_number','name','status','unique_number','tahun'];



}
