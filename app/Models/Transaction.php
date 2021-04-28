<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 *
 * @property $id
 * @property $customer_id
 * @property $status
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Customer $customer
 * @property Payment[] $payments
 * @property Shipping[] $shippings
 * @property TransactionItem[] $transactionItems
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transaction extends Model
{
    use SoftDeletes;

    static $rules = [
		'customer_id' => 'required',
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id','status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'transaction_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'transaction_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippings()
    {
        return $this->hasMany('App\Models\Shipping', 'transaction_id', 'id');
    }

    public function shipping()
    {
        return $this->hasOne('App\Models\Shipping', 'transaction_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactionItems()
    {
        return $this->hasMany('App\Models\TransactionItem', 'transaction_id', 'id');
    }

    public function getSubtotalAttribute()
    {
        return $this->transactionItems()->sum('total');
    }

    public function getSubtotalFormatedAttribute()
    {
        return number_format($this->subtotal);
    }

    public function getTotalAttribute()
    {
        $item_total = $this->transactionItems()->sum('total');
        $shipping   = $this->shipping->service_rates;
        $payment    = $this->payment->admin_fee;
        return $item_total+$shipping+$payment;
    }

    public function getTotalFormatedAttribute()
    {
        return number_format($this->total);
    }
    

}
