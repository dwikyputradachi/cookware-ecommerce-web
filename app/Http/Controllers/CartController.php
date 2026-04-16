<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{    public function index()
    {
        return view('cart.index');
    }

    public function add($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        return back(); // biar balik ke halaman sebelumnya
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            
            $request->merge(json_decode($request->getContent(), true) ?? []);
            if($request->action == 'plus') {
                $cart[$id]['quantity']++;
            }

            if($request->action == 'minus') {
                $cart[$id]['quantity']--;

                if($cart[$id]['quantity'] <= 0){
                    unset($cart[$id]);
                }
            }

            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }
}