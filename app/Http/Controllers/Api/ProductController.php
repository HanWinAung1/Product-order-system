<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Psy\Util\Json;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }
}
