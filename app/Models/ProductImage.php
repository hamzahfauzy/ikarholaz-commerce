<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 *
 * @property $id
 * @property $product_id
 * @property $file_url
 *
 * @property Product $product
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ProductImage extends Model
{
    
    static $rules = [
		'product_id' => 'required',
		'file_url' => 'required',
    'image_type' => 'nullable'
    ];

    public $timestamps = false;

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','file_url','image_type'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    

}
