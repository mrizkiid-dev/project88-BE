<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductUploadRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Utils\CheckerHelper;
use App\Utils\ResponseErrorHelper;
use App\Utils\ResponsePaginationHelper;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    //
    public function test()
    {
        $data = ProductImage::where('product_id',61)->get();
        return response()->json($data);
    }

    public function index(ProductSearchRequest $request)
    {
        try {
            $request->validated();

            $data = Product::query();
            $data->with(['productCategory', 'productImage']);
            $data->where(function(Builder $builder) use ($request) {
                $sku = $request->query('name', '');
                if($sku) {
                    $builder->where('SKU', 'like','%'.$sku.'%');
                } 

                $name = $request->query('name', '');
                if($name) {
                    $builder->where('name', 'like','%'.$name.'%');
                }
            });

            $sortBySellOut = $request->input('sort-by-sellout', 'asc');
            if($sortBySellOut === 'desc') {
                $data->orderBy('sell_out', 'desc');
            }

            $shortByDate = $request->input('sort-by-date', 'asc');
            if($shortByDate === 'desc') {
                $data->orderBy('created_at', 'desc');
            }

            $page = $request->input('page', 1);
            $size = $request->input('size', 10);
            $data = $data->paginate(perPage: $size, page: $page);
            $meta = ResponsePaginationHelper::getPaginationMetadata($data);

            return response()->json([
                'meta' => $meta,
                'data' => ProductResource::collection($data)   
            ],200);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function storeProduct(ProductUploadRequest $request)
    {
        try {
            $request->validated();
            $payload = json_decode($request->payload, true);
            $this->checkCategoryExist($payload['category']['id']);

            DB::beginTransaction();
            $data = Product::create([
                'category_id' => $payload['category']['id'],
                'SKU' => $payload['sku'],
                'name' => $payload['name'],
                'desc' => $payload['description'],
                'price' => $payload['price'],
                'discount' => $payload['discount'],
                'qty' => $payload['qty'],
                'sell_out' => 0,
                'weight' => $payload['weight'],
            ]);

            if($request->hasFile('images')) {
                $productImagePayload = [];
                foreach ($request->images as $image) {
                    $imageName = time().'_'.$image->getClientOriginalName();
                    $storage = Storage::disk('public');
                    Storage::disk('public')->put('products/'.$imageName, file_get_contents($image));
                    $url = Storage::disk('public')->url('products/'.$imageName);

                    array_push($productImagePayload,new ProductImage([
                        'image_url' => $url,
                        'name' => $imageName,
                        'path' => 'products/'.$imageName,
                        'created_at' => now(),
                        'modified_at' => now()
                    ]));
                }
                $data->productImage()->saveMany($productImagePayload);
            }
            DB::commit();

            return response()->json([
                'message' => 'product has been upload',
                'data' => new ProductResource($data),
            ]);
        } catch (\Throwable $e) {
            DB::rollback();
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function getWithId($id) {
        try {
            CheckerHelper::isDigit($id);
            $data = Product::find($id);
            if(!$data) {
                throw new HttpException(404,'Product not found');
            }
            return new ProductResource($data);
        } catch (\Throwable $e) {
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function patchWithId(ProductUpdateRequest $request, $id) {
        try {
            DB::beginTransaction();

            $request->validated();
            $payload = json_decode($request->payload, true);
            if(isset($payload['category']['id'])) {
                $this->checkCategoryExist($payload['category']['id']);
            }

            $data = Product::find($id);
            if(isset($payload['sku'])) {
                $data->SKU = $payload['sku'];
            }
            if(isset($payload['name'])) {
                $data->name = $payload['name'];
            }
            if(isset($payload['category']['id'])) {
                $data->category_id = $payload['category']['id'];
            }
            if(isset($payload['description'])) {
                $data->desc = $payload['description'];
            }
            if(isset($payload['price'])) {
                $data->price = $payload['price'];
            }
            if(isset($payload['discount'])) {
                $data->discount = $payload['discount'];
            }
            if(isset($payload['qty'])) {
                $data->qty = $payload['qty'];
            }
            if(isset($payload['sell_out'])) {
                $data->sell_out = $payload['sell_out'];
            }
            if(isset($payload['weight'])) {
                $data->weight = $payload['weight'];
            }

            $data->save();
            if(isset($payload['deleteIds'])){
                $deleteIds = $payload['deleteIds'];
                $dataDelete = ProductImage::whereIn('id', $deleteIds)->get(['name']);

                $imagesDelete = [];
                foreach ($dataDelete as $imageDelete) {
                   array_push($imagesDelete, 'products/'.$imageDelete->name);
                }

                $data->productImage()->whereIn('id', $deleteIds)->delete();
                Storage::disk('public')->delete($imagesDelete);
            }

            if($request->hasFile('images')) {
                $productImagePayload = [];
                foreach ($request->images as $image) {
                    $imageName = time().'_'.$image->getClientOriginalName();
                    Storage::disk('public')->put('products/'.$imageName, file_get_contents($image));
                    $url = Storage::disk('public')->url('products/'.$imageName);

                    array_push($productImagePayload,new ProductImage([
                        'image_url' => $url,
                        'name' => $imageName,
                        'path' => 'products/'.$imageName,
                        'created_at' => now(),
                        'modified_at' => now()
                    ]));
                }
                Log::debug('kok supabase sih = '.json_encode($productImagePayload));
                $data->productImage()->saveMany($productImagePayload);
            }
            DB::commit();
            $product = Product::find($id);
            return response()->json([
                'message' => 'patch with id '.$id,
                'data' => new ProductResource($product)
            ]);
        } catch (\Throwable $e) {
            Log::debug('error product patch = '.$e->getMessage().'xxxx'.$e->getTrace());
            DB::rollBack();
            return ResponseErrorHelper::throwErrorResponse($e);
        }
    }

    public function delete($id)
    {
        try {
            $data = Product::find($id);
            if(!$data) {
                throw new HttpException(statusCode: 404, message: 'data '.$id.' is not found');
            }
            DB::beginTransaction();

            $data->productImage()->delete();
            $data->delete();

            DB::commit();
        
            return response()->json([
                'success' => true,
                'message' => 'delete with '.$id
            ]); 
        } catch (\Throwable $e) {
            # code...
            DB::rollBack();
            return ResponseErrorHelper::throwErrorResponse($e);
        }   
    }

    private function checkCategoryExist($id)
    {
        $isCategoryExist = ProductCategory::where('id', $id)->first();
        if(!$isCategoryExist) {
            throw new HttpException(404, 'category doesn\'t exist');
        }
    }
}
