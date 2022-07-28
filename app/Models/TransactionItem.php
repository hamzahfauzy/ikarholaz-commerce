<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TransactionItem
 *
 * @property $id
 * @property $transaction_id
 * @property $product_id
 * @property $amount
 * @property $total
 *
 * @property Product $product
 * @property Shipping[] $shippings
 * @property Transaction $transaction
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TransactionItem extends Model
{
    public $timestamps = false;
    static $rules = [
		'transaction_id' => 'required',
		'product_id' => 'required',
		'amount' => 'required',
		'total' => 'required',
		'notes' => 'nullable',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['transaction_id','product_id','amount','total','notes'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippings()
    {
        return $this->hasMany('App\Models\Shipping', 'transaction_item_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'id', 'transaction_id');
    }

    public function getTotalFormatedAttribute()
    {
        return number_format($this->total);
    }

    public function getCustomFieldsAttribute()
    {
        $custom_fields = CustomField::where('class_target','App\Models\TransactionItem')->get();
        $custom_field_values = [];
        foreach($custom_fields as $custom_field)
        {
            if($custom_field->get_value($this->id))
                $custom_field_values[] = $custom_field->get_value($this->id);
        }
        return $custom_field_values;
    }
    

}
