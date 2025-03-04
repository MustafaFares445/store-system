<?php

namespace App\Http\Controllers\Public;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="List all products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductResource")
     *         )
     *     )
     * )
     */
    public function index()
    {

    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     summary="Get product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(Product $product): ProductResource
    {
        return ProductResource::make(
            $product->load([
                'media', 'attributes.media',
                'productVariants.media',  'productVariants.attributes',
                'category.media', 'reviews', 'status'
            ])
        );
    }

    public function relatedProducts(Product $product)
    {
        $relatedProducts = new Collection();

        $relatedProducts = $product->relatedProducts()
            ->with(['media'])
            ->select(['id', 'name', 'slug', 'summary', 'view' , 'price'])
            ->get();

        if($relatedProducts->count() < 5){
            $relatedProducts->push($product->vendors()->shuffle()->each(function($vendor){
                return $vendor->products()
                    ->with(['media'])
                    ->select(['id', 'name', 'slug', 'summary', 'view' , 'price']) 
                    ->shuffle()
                    ->take(5)
                    ->get();
            }));
        }

        if($relatedProducts->count() < 5){
            $relatedProducts->push(
                $product->category()->products()
                ->with(['media'])
                ->select(['id', 'name', 'slug', 'summary', 'view' , 'price'])
                ->shuffle()
                ->take(5)
                ->get()
            );
        }

        return ProductResource::collection($relatedProducts);
    }
}