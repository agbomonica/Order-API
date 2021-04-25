<?php

namespace App\Http\Controllers\API;
use App\Models\Order;
use App\Utilities\GoogleMap;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Client\Response;

class PlaceOrderController extends Controller
{
    public function store(Request $request)

    {
        $validation = request()->validate([
                'origin' => 'required',
                'destination' => 'required',


        ]);
        $source = request('origin');
        $destination = request('destination');

        $geocodeOutput = GoogleMap::geocoding($source, $destination);
        $geocode = collect($geocodeOutput);

        if(isset($geocode['error'])){

            return response([
                'status' => 'fail',
                'code' => 404,
                'message' => $geocode['error'],
            ])->setStatusCode(404);
        }
        else {

        $order = Order::create([
            'distance' => $geocode['distance'],
            'origin' => $geocode['origin']['address'],
            'destination' => $geocode['destination']['address'],
            'status' => 'Unassigned',
        ]);

        if ($order) {

            return response([
                'status' => 'success',
                'code' => 201,
                'message' => "Order Placed successfully",
                'data' => new OrderResource ($order),

            ])->setStatusCode(201);
        }
        else {

            return response([
                'status' => 'fail',
                'code' => 400,
                'message' => "Unable to place Order",
            ])->setStatusCode(400);
        }

    }

    }
}
