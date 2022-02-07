<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
        // $this->middleware('auth:api')->only(['read']);

    }

    public function store(Request $request) {
        $data = $request->only('product_name', 'price', 'quantity');

        $validator = Validator::make($data, [
            'product_name' => ['required', 'string'],
            'price' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validData = array_merge($validator->validated(), ['quantity_remaining' => $data['quantity']]);
        // dd($inventory);

        $inventory = auth()->user()->inventories()->create($validData);
        if ($inventory) {
            $info = [
                'status' => 'success',
                'message' => 'Inventory created successfully',
                'data' => [
                    'inventory' => $inventory
                ]
            ];

            return response()->json($info, 201);
        }

        $info = [
            'status' => 'error',
            'message' => 'Inventory cannot be created',
        ];

        return response()->json($info, 422);


    }

    public function update($id, Request $request) {
        $data = $request->only('product_name', 'price', 'quantity');

        $validator = Validator::make($data, [
            'product_name' => ['string'],
            'price' => ['integer'],
            'quantity' => ['integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if(Inventory::where('id', $id)->exists()) {

            $inventory = Inventory::find($id);
            $inventory->product_name = is_null($request->product_name)  ? $inventory->product_name : $request->product_name;
            $inventory->price = is_null($request->price)  ? $inventory->price : $request->price;
            $inventory->quantity = is_null($request->quantity)  ? $inventory->quantity : $request->quantity;
            $inventory->quantity_remaining = is_null($request->quantity) ? $inventory->quantity : $request->quantity;
            $inventory->user_id = auth()->id();
            $inventory->update();

            $info = [
                'status' => 'success',
                'message' => 'Inventory updated successfully',
                'data' => [
                    'inventory' => $inventory
                ]
            ];

            return response()->json($info, 200);

        } else {

            $info = [
                'status' => 'error',
                'message' => 'Inventory not found',
            ];

            return response()->json($info, 404);
        }



    }

    public function readAll() {
        $inventories = Inventory::latest()->paginate(5);
        $info = [
            'status' => 'success',
            'message' => 'All inventories retrieved',
            'data' => [
                'inventories' => $inventories
            ]
        ];
        return response()->json($info, 200);

    }

    public function read($id) {
        if(Inventory::where('id', $id)->exists()) {
            $inventory = Inventory::find($id);
            $info = [
                'status' => 'success',
                'message' => 'Inventory retrieved',
                'data' => [
                    'inventory' => $inventory
                ]
            ];

            return response()->json($info, 200);

        } else {
            $info = [
                'status' => 'error',
                'message' => 'Inventory not found',
            ];

            return response()->json($info, 404);

        }
    }

    public function delete($id) {
        if (Inventory::where('id', $id)->exists()) {
            $inventory = Inventory::find($id);
            $inventory->delete();

            $info = [
                'status' => 'success',
                'message' => "inventory deleted"
            ];

            return response()->json($info, 202);

        } else {
            $info = [
                'status' => 'error',
                'message' => 'Inventory not found',
            ];

            return response()->json($info, 404);
        }
    }
}
