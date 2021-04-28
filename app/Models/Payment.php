<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 *
 * @property $id
 * @property $transaction_id
 * @property $total
 * @property $admin_fee
 * @property $checkout_url
 * @property $payment_type
 * @property $merchant_ref
 * @property $status
 * @property $payment_reference
 * @property $payment_code
 * @property $expired_time
 * @property $created_at
 * @property $updated_at
 *
 * @property Transaction $transaction
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Payment extends Model
{
    
    static $rules = [
		'transaction_id' => 'required',
		'total' => 'required',
		'checkout_url' => 'required',
		'payment_type' => 'required',
		'merchant_ref' => 'required',
		'status' => 'required',
		'payment_reference' => 'required',
		'payment_code' => 'required',
		'expired_time' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['transaction_id','total','admin_fee','checkout_url','payment_type','merchant_ref','status','payment_reference','payment_code','expired_time'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'id', 'transaction_id');
    }

    public function getAdminFeeFormatedAttribute()
    {
        return number_format($this->admin_fee);
    }

    public function getTotalFormatedAttribute()
    {
        return number_format($this->total);
    }
    

}
