<?php

namespace Modules\Products\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Products\Models\Products;
use Modules\Products\Models\Files;
use Modules\Products\Models\ProductsHistory;
use Modules\Products\Services\ProductsServiceInterface;

class ProductsService implements ProductsServiceInterface
{
    private $productModel;

    private $productHistoryModel;

    private $filesModel;

    /**
     * Represents product status
     * 
     * @var string PUBLISHED 
     */
    private const PUBLISHED = 'published';

    /**
     * Represents product status
     * 
     * @var string TRASH
     */
    private const TRASH = 'trash';
    
    /**
     * Represents product status
     * 
     * @var string DRAFT
     */
    private const DRAFT = 'draft';

    /**
     * Create a new class instance.
     */
    public function __construct(Products $product, ProductsHistory $productHistory, Files $files)
    {
        $this->productModel = $product;
        $this->productHistoryModel = $productHistory;
        $this->filesModel = $files;
    }

    /**
     * Reduces the columns from the data file
     * to only the columns that are going to be
     * persisted into the database.
     *
     * @param array $data
     * @param string $status
     * @param integer $fileId
     * @return array
     */
    private function formatData(array $data, string $status, int $fileId): array
    {
        return array_map(function($product) use ($fileId, $status) {
            return [
                'code' => trim($product['code'], '"'),
                'file_id' => $fileId,
                'status' => $status,
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
        }, $data);
    }

    /**
     * Set everything from the products table
     * as a draft where file_id => $fileId
     *
     * @param integer $fileId
     * @return void
     */
    public function setAsDraft(int $fileId) {
        Log::channel('importer')->info("2. Setting products from fileId {$fileId} as draft...");
        $this->productModel
            ->where(['file_id' => $fileId])
            ->update(['status' => 'draft']);
    }

    /**
     * Saves data from the files into the database
     *
     * @param array $data
     * @param integer $fileId
     * @return void
     */
    public function save(array $data, int $fileId)
    {
        $products = $this->formatData($data, self::PUBLISHED, $fileId);
        $this->productModel::insert($products);
    }

    /**
     * Saves all published products to the history table
     * changing its statsu to "trash" where file_id => $fileId
     *
     * @param integer $fileId
     * @return void
     */
    public function saveHistory(int $fileId)
    { 
        Log::channel('importer')->info("----------------");
        Log::channel('importer')->info("1. Saving products from fileId {$fileId} to history");
        $products = $this->productModel::where([
            'file_id' => $fileId,
            'status' => self::PUBLISHED
        ])->get();

        if ($products->count() == 0) {
            return '';
        }

        $trashProducts = $products->map(function($product) {
            $product->status = self::TRASH;
            return $product;
        });

        $this->productHistoryModel::insert($trashProducts->toArray());
    }

    /**
     * Delete every draft product from the products table
     *
     * @param integer $fileId
     * @return void
     */
    public function delete(int $fileId)
    {
        Log::channel('importer')->info("3. Cleaning products table from fileId {$fileId}");
        Log::channel('importer')->info("----------------");
        $this->productModel::where(['file_id' => $fileId])
            ->where(['status' => self::DRAFT])
            ->delete();
    }

    /**
     * Get the id of $fileName from files table
     * allowing us to know from which file a
     * product came from
     *
     * @param string $fileName
     * @return integer
     */
    public function getFileNameId(string $fileName): int
    {
        $result = $this->filesModel::where(['name' => $fileName])->first();
        return $result->id;
    }

    /**
     * Return 100 products and links to next pages
     *
     * @return 
     */
    public function getAll(): Paginator {
        return $this->productModel
            ->where(['status' => self::PUBLISHED])
            ->simplePaginate(100);
    }

    /**
     * Returns the product with code = $code
     *
     * @param string $code
     * @return Paginator
     */
    public function getById(string $code): Paginator
    {
        return $this->productModel
            ->where(['status' => self::PUBLISHED])
            ->where(['code' => $code])
            ->simplePaginate();
    }
}
