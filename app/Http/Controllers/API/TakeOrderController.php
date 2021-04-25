<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TakeOrderController extends Controller
{
    public function update(Request $request, $id)
    {
            $validated = $request->validate([
                'status' => 'required',
            ]);
            $order = Order::find($id);

            if($order->status === 'Unassigned'){

                $takeOrder = $order->update(['status' => request('status')]);

                if (! $takeOrder){
                return response([
                'status' => 'fail',
                'code' => 400,
                'message' => "Unable to take Order",
            ])->setStatusCode(400);

            }
            return response([
                'status' => 'success',
                'code' => 200,
                'message' => "Order taken successfully",

            ])->setStatusCode(200);


            }else {
                return response([
                'status' => 'fail',
                'code' => 400,
                'message' => "Order has already been taken!",
            ])->setStatusCode(400);


            }


    }
}
