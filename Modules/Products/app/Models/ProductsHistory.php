<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Products\Database\Factories\ProductsHistoryFactory;

class ProductsHistory extends Model
{
    use HasFactory;

    // protected $table = 'products_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'status',
        'imported_t',
        'url',
        'creator',
        'created_t',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
    ];

    protected static function newFactory(): ProductsHistoryFactory
    {
        //return ProductsHistoryFactory::new();
    }
}
