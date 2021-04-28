<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * @property $id
 * @property $name
 * @property $slug
 * @property $deleted_at
 * @property $created_at
 * @property $updated_at
 *
 * @property ProductCategory[] $productCategories
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model
{
    use SoftDeletes;

    static $rules = [
      'name' => 'required',
      'slug' => 'sometimes|required|alpha_dash|unique:categories',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productCategories()
    {
        return $this->hasMany('App\ProductCategory', 'category_id', 'id');
    }

    public function products()
    {
      return $this->belongsToMany(Product::class,'product_categories');
    }
    

}