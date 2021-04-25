<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Utilities\GoogleMap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderListResource;
use Symfony\Component\HttpFoundation\Response;

class OrderListController extends Controller
{

    public function index(Request $request, $page=1)

    {

        if($request->input('page') && $request->input('limit')){

            $request->validate([
            'page' => 'filled | numeric | min:1',
            'limit' => 'filled | numeric | min:1 | between:1,100',

            ]);

            $query = Order::query();

            $page = $request->input('page');

            $perPage = $request->input('limit');

            $total = $query->count();
            $orderList = $query->offset(($page - 1 ) * $perPage)->limit($perPage)->get();

            return [
            'data' => OrderListResource::collection ($orderList),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'last_page' => ceil($total / $perPage),

        ];
            }else {
                $orderList = Order::get();
                return response([
                'status' => 'success',
                'code' => 200,
                'data' => OrderListResource::collection ($orderList),
            ])->setStatusCode(200);
            }


        }
}
