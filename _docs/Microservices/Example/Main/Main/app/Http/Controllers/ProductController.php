<?php

namespace App\Http\Controllers;

use App\Jobs\ProductLiked;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function like(Request $request, $id)
    {
        $response = Http::get('http://docker.for.win.localhost:8000/api/user');

        $user = $response->json();

        try {
            $productUser = ProductUser::query()->create([
                'user_id' => $user['id'],
                'product_id' => $id,
            ]);

            ProductLiked::dispatch($productUser->toArray())->onQueue('admin_queue');

            return response([
                'message' => 'success',
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => 'Bu mahsulotga like bosgansiz!'.$e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
