<?php

namespace Modules\Products\Services;

use Modules\Products\Models\Products;
use Modules\Products\Models\Files;
use Modules\Products\Services\ProductsServiceInterface;

class ProductsService implements ProductsServiceInterface
{
    private $productModel;

    private $filesModel;

    /**
     * Create a new class instance.
     */
    public function __construct(Products $product, Files $files)
    {
        $this->productModel = $product;
        $this->filesModel = $files;
    }

    public function save(array $data, int $fileId)
    {
        $data = collect($data);
        $products = $data->map(function($product) use ($fileId) {
            return [
                'code' => trim($product['code'], '"'),
                'file_id' => $fileId,
                'status' => 'published',
                'imported_t' => (new \DateTime())->format('Y-m-d H:i:s'),
                'url' => $product['url'],
                'creator' => $product['creator'],
                'created_t' => $product['created_t'],
                'last_modified_t' => $product['last_modified_t'],
                'product_name' => $product['product_name'],
                'quantity' => $product['quantity'],
                'brands' => $product['brands'],
                'categories' => $product['categories'],
                'labels' => $product['labels'],
                'cities' => $product['cities'],
                'purchase_places' => $product['purchase_places'],
                'stores' => $product['stores'],
                'ingredients_text' => $product['ingredients_text'],
                'traces' => $product['traces'],
                'serving_size' => $product['serving_size'],
                'serving_quantity' => empty($product['serving_quantity']) ? 0.0 : $product['serving_quantity'],
                'nutriscore_score' => empty($product['nutriscore_score']) ? 0 : $product['nutriscore_grade'],
                'nutriscore_grade' => $product['nutriscore_grade'],
                'main_category' => $product['main_category'],
                'image_url' => $product['image_url'],
            ]; 
        });

        $this->productModel::insert($products->toArray());
    }

    public function getFileNameId(string $fileName): int
    {
        $result = $this->filesModel::where(['name' => $fileName])->first();
        return $result->id;
    }
}
