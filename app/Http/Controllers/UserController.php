<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function read($id) {
        if(Inventory::where('id', $id)->exists()) {
            $inventory = Inventory::find($id)->get();
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
