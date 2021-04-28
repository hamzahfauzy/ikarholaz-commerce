<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 *
 * @property $id
 * @property $user_id
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $province_id
 * @property $district_id
 * @property $subdistrict_id
 * @property $address
 * @property $postal_code
 * @property $phone_number
 * @property $created_at
 * @property $updated_at
 * @property $province_name
 * @property $district_name
 * @property $subdistrict_name
 *
 * @property Transaction[] $transactions
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Customer extends Model
{
    
    static $rules = [
		'first_name' => 'required',
		'last_name' => 'required',
		'email' => 'required',
		'province_id' => 'required',
		'district_id' => 'required',
		'address' => 'required',
		'postal_code' => 'required',
		'phone_number' => 'required',
		'province_name' => 'required',
		'district_name' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','first_name','last_name','email','province_id','district_id','subdistrict_id','address','postal_code','phone_number','province_name','district_name','subdistrict_name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'customer_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    

}
