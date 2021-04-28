<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 *
 * @property $id
 * @property $name
 * @property $slug
 * @property $description
 * @property $price
 * @property $stock
 * @property $stock_status
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property ProductCategory[] $productCategories
 * @property ProductImage[] $productImages
 * @property ProductVariant[] $productVariants
 * @property ProductVariant[] $productVariants
 * @property TransactionItem[] $transactionItems
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{
    use SoftDeletes;

    static $rules = [
		'name' => 'required',
		'slug' => 'sometimes|required|alpha_dash|unique:products',
		'description' => 'required',
		'base_price' => 'required',
		'discount_price' => 'nullable',
		'stock' => 'required',
		'stock_status' => 'nullable',
		'is_dynamic' => 'nullable',
		'product_weight' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug','description','base_price','discount_price','stock','stock_status','is_dynamic','product_weight'];

    public function getPriceAttribute()
    {
        return $this->discount_price ? $this->discount_price : $this->base_price;
    }

    public function getPriceFormatedAttribute()
    {
        return number_format($this->price);
    }

    public function getStockLabelAttribute()
    {
        return $this->stock_status ? $this->stock_status : $this->stock;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'product_categories','product_id','category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function thumb()
    {
        return $this->hasOne('App\Models\ProductImage');
    }

    public function getThumbnailAttribute()
    {
        $thumbnail = $this->thumb;
        return $thumbnail?\Storage::url($thumbnail->file_url):asset('front/images/properties/1.jpg');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne('App\Models\ProductVariant', 'product_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->belongsToMany('App\Models\Product', 'product_variants', 'parent_id', 'product_id');
    }

    public function getAddToCartUrlAttribute()
    {
        if($this->categories->contains('name','Kartu Anggota'))
            return 'Kartu Anggota';
        if($this->categories->contains('name','Nomor Kartu Anggota'))
            return 'Nomor Kartu';
        return 'Produk Biasa';
        
    }

    public function getIsNewAttribute()
    {
        $date1 = $this->created_at->format('Y-m-d');
        $date2 = "now";

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        
        return $days <= 7;
    }

    public function getCustomFieldsAttribute()
    {
        $custom_fields = CustomField::where('class_target','App\Models\Product')->get();
        $custom_field_values = [];
        foreach($custom_fields as $custom_field)
        {
            if($custom_field->get_value($this->id))
                $custom_field_values[] = $custom_field->get_value($this->id);
        }
        return $custom_field_values;
    }

    function set_custom_fields($data)
    {
        foreach($data as $key => $value)
        {
            $custom_field = CustomField::where('class_target','App\Models\Product')->where('field_key',$key);
            if(!$custom_field->exists()) continue;
            $custom_field = $custom_field->first();
            CustomFieldValue::create([
                'custom_field_id' => $custom_field->id,
                'pk_id' => $this->id,
                'field_value' => $value
            ]);
        }
    }
}
