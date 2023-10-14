<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $pagination_limit = config('data.pagination_limit'); // get pagination limit value from config
            $products = Product::with('images')->paginate($pagination_limit);
            return view('products', compact('products'));
        } catch (Throwable $th) {
            Log::debug('Get Products error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }

    public function show(Product $product)
    {
        try {
            $product->load('images', 'feedbacks.comments', 'feedback.attachments', 'feedback.category'); // eager load relational data
            return view('product', compact('product'));
        } catch (Throwable $th) {
            Log::debug('Show products error on line: ' . $th->getLine());
            Log::error('Error: ' . $th->getMessage());
            Log::info('************************************************************************************************');
            return ResponseService::errorResponse('Something went wrong.');
        }
    }
}
