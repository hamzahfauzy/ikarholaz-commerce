<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomFieldValue
 *
 * @property $id
 * @property $custom_field_id
 * @property $pk_id
 * @property $field_value
 *
 * @property CustomField $customField
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CustomFieldValue extends Model
{
    public $timestamps = false;
    static $rules = [
		'custom_field_id' => 'required',
		'pk_id' => 'required',
		'field_value' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['custom_field_id','pk_id','field_value'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customField()
    {
        return $this->hasOne('App\Models\CustomField', 'id', 'custom_field_id');
    }
    

}
