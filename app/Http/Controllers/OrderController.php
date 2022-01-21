<?php

namespace App\Http\Controllers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\{
    GenericHelper,
    OrderHelper
};
use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    /**
     * Returns a closure that:
     * Displays a listing of the resource.
     *
     * @return Closure
     */
    public static function index(): Closure
    {
        return function (Request $req): array {
            $cache_key = 'schedules_' . $req->authData['tokenable_id'];

            return [$cache_key, function () use ($req): array {
                $data = OrderHelper::getIndexRequestData($req);
                $orders = OrderHelper::getOrders($data);

                return [$orders, 200, 60];
            }];
        };
    }
    /**
     * Returns a closure that:
     * Stores a newly created resource in storage.
     * 
     * @return Closure
     */
    public static function store(): Closure
    {
        return function (Request $req): array {
            return [null, function () use ($req): array {
                $data = OrderHelper::getStoreRequestData($req);

                $order = OrderHelper::handleStoreRequest($data);

                return [$order, 201, 0];
            }];
        };
    }

    public static function change(): Closure
    {
        return function (Request $req): array {
            GenericHelper::validate(Order::getChangeRequestValidator([
                'uuid' => $req->uuid,
                'establishmentUuid' => $req->authData['tokenable_id'],
                'status' => $req->orderStatus,
            ]));

            return ["order_" . $req->uuid, function () use ($req): array {
                $queryData = ['uuid' => $req->uuid];
                $status = $req->orderStatus;
                $establishmentUuid = $req->authData['tokenable_id'];

                $order = OrderHelper::getOrder($queryData);

                if ($status === 'accepted') {
                    if ($order->status !== 'pending') {
                        throw new GenericAppException([__('messages.not_pending_order')], 422);
                    }

                    $orders = Order::where([
                        'establishmentUuid' => $establishmentUuid,
                        'status' => 'accepted',
                    ])->get();

                    $numOfOrders = sizeof($orders);

                    if ($numOfOrders >= $order->schedule->maxServices) {
                        throw new GenericAppException([__('messages.max_orders_limit_reached')], 422);
                    }

                    $order->status = $status;
                    $order->save();

                    if (($numOfOrders + 1) >= $order->schedule->maxServices) {
                        Order::where([
                            'scheduleId' => $order->scheduleId,
                            'day' => $order->day,
                            'status' => 'pending',
                        ])->update([
                            'status' => 'refused',
                        ]);
                    }
                } else if ($status === 'completed') {
                    if ($order->status !== 'accepted') {
                        throw new GenericAppException([__('messages.not_accepted_order')], 422);
                    }

                    $order->status = $status;
                    $order->save();
                } else {
                    $order->status = $status;
                    $order->save();
                }

                Cache::forget('schedules_' . $establishmentUuid);

                return [$order, 200, 60];
            }];
        };
    }
}
