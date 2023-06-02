<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Auth;

class ProductController extends Controller
{
    public function productList()
    {
        $data['products'] = Products::getAllProducts();
        return view('products_list', $data);
    }

    public function productDetails(Request $request)
    {
        $data['product'] = Products::getProductDetails(base64_decode($request->id));
        $user = Auth::user();
        $data['user'] = $user;
        $data['intent'] = $user->createSetupIntent();
        return view('product_details', $data);
    }
}
