<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function shoppingCart()
    {
        return view('shopping.cart');
    }

    public function shoppingList()
    {
        $user = Auth::user();
        return view('shopping.list', [
            'purchases' => $user->allPurchases->count()
        ]);
    }

    public function purchasedProducts()
    {
        $user = User::find(Auth::user()->id);
        $products = $user->purchasedProducts;
        return view('shopping.purchased_products', compact('products'));
    }
}
