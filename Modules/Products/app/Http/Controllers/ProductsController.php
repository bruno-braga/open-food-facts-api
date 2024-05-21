<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Products\Services\ProductsServiceInterface;
use Modules\Products\Transformers\ProductsResource;

class ProductsController extends Controller
{
    private $productsService;
    /**
     * Class constructor.
     */
    public function __construct(ProductsServiceInterface $productsService)
    {
        $this->productsService = $productsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            $this->productsService->getAll()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $req, string $code)
    {
        return response()->json(
            new ProductsResource($this->productsService->getById($code))
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('products::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
