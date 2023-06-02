<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public static function getAllProducts()
    {
        return Products::all();
    }

    public static function getProductDetails($id)
    {
        return Products::find($id);
    }
}
