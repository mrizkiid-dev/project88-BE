<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\SortEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderUpdateStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Utils\ResponsePaginationHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderController extends Controller
{
    public function get(Request $request) {
        try {
            //parsing request
            $shortByDate = $request->input('sort-by-date', 'asc');
            $page = $request->input('page', 1);
            $size = $request->input('size', 10);

            $data = Order::query()->where(function (Builder $builder) use ($request) {
                $name = $request->input('name-receiver', '');
                if($name) {
                    $builder->where('name_receiver', $name);
                }
            });

            if ($shortByDate === 'desc') {
                $data->orderBy('created_at', 'desc');
            } else {
                $data->orderBy('created_at');
            }

            $data = $data->paginate(perPage: $size, page: $page);
            $meta = ResponsePaginationHelper::getPaginationMetadata($data);

            return response()->json([
                'message' => 'get order success',
                'meta' => $meta,
                'data' => OrderResource::collection($data)
            ],200);
        } catch (\Throwable $e) {
            if ($e instanceof HttpException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ],$e->getCode());
            }

            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    public function getWithId($id) {
        try {
            $pattern = '/^[0-9]+$/';
            if($id && !preg_match($pattern,$id)){
                throw new HttpException(404, 'Not Found');
            }

            $data = Order::where('id', $id)->first();
            return new OrderResource($data);

        } catch (\Throwable $e) {
            if ($e instanceof HttpException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ],$e->getStatusCode());
            }

            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    private function stringToShortEnum(string $value) {
        return SortEnum::from($value);
    }

    public function updateStatusOrder(OrderUpdateStatusRequest $request, $id) {
        try {
            $request->validated();
            $pattern = '/^[0-9]+$/';
            if(!preg_match($pattern,$id)){
                throw new HttpException(404, 'Not Found');
            }

            $order = Order::find($id);
            $order->status = $request->status_order['status'];
            $order->save();

            $data = Order::find($id);
            return response()->json([
                'success' => true,
                'message' => 'patch order with id '.$id.' success',
                'data' => new OrderResource($data)
            ],200);

            
        } catch (\Throwable $e) {
            if ($e instanceof HttpException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ],$e->getStatusCode());
            }

            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }
}
