<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addInventoryToCart($id, Request $request) {
        $data = $request->only('quantity_bought');

        $validator = Validator::make($data, [
            'quantity_bought' => ['integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (Inventory::where('id', $id)->exists()) {
            $inventory = Inventory::find($id);

            if($inventory->quantity_remaining < $data['quantity_bought']) {
                $info = [
                    'status' => 'error',
                    'message' => 'Number of items are too much',
                ];
                return response()->json( $info,422);
            }

            $cartItems = auth()->user()->cart()->create([
                'quantity_bought' => $data['quantity_bought'],
                'user_id' => auth()->id(),
                'inventory_id' => $id
            ]);

            if ($cartItems) {
                $info = [
                    'status' => 'success',
                    'message' => 'Items successfully added to cart',
                    'data' => [
                        'cartItems' => $cartItems
                    ]
                ];

                $quantity_remaining = $inventory->quantity_remaining - $data['quantity_bought']; // Quantity remaining in db after adding to cart
                $quantity_sold = $inventory->quantity_sold + $data['quantity_bought']; //Quantity sold in db after adding to cart

                $inventory->quantity_remaining = $quantity_remaining;
                $inventory->quantity_sold = $quantity_sold;
                $inventory->update();

                return response()->json($info, 201);
            } else {
                $info = [
                    'status' => 'error',
                    'message' => 'Unable to add to cart',
                ];
                return response()->json($info, 422);

            }

        } else {
            $info = [
                'status' => 'error',
                'message' => 'Inventory not found',
            ];

            return response()->json($info, 404);
        }
    }
}
