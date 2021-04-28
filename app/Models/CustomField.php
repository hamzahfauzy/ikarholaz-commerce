<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomField
 *
 * @property $id
 * @property $field_key
 * @property $field_type
 * @property $class_target
 * @property $query_condition
 * @property $created_at
 * @property $updated_at
 *
 * @property CustomFieldValue[] $customFieldValues
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CustomField extends Model
{
    
    static $rules = [
		'field_key' => 'required',
		'field_type' => 'required',
		'class_target' => 'required',
		'query_condition' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['field_key','field_type','class_target','query_condition'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customFieldValues()
    {
        return $this->hasMany('App\Models\CustomFieldValue', 'custom_field_id', 'id');
    }

    public function get_value($pk_id)
    {
      return $this->customFieldValues()->where('pk_id',$pk_id)->first();
    }
    

}
