<?php

namespace App\Http\Controllers;

use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function show(int $id): Product
    {
        return Product::query()->find($id);
    }

    public function store(Request $request)
    {
        $product = Product::query()->create($request->only('title', 'image'));

        ProductCreated::dispatch($product->toArray());

        return response($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $product = Product::query()->find($id);

        abort_if(!$product, Response::HTTP_NOT_FOUND, 'Product not found');

        $product->update($request->only('title', 'image'));

        ProductUpdated::dispatch($product->toArray());

        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Product::destroy($id);

        ProductDeleted::dispatch($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
