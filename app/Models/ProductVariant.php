<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductVariant
 *
 * @property $id
 * @property $product_id
 * @property $parent_id
 *
 * @property Product $product
 * @property Product $product
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductVariant extends Model
{
    
    static $rules = [
		'product_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','parent_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne('App\Models\Product', 'id', 'parent_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    

}
