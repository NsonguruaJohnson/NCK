<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
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

}
