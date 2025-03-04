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
                'productVariants.media',  'productVariants.attributes.media',
                'category.media', 'reviews', 'status'
            ])
        );
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}/related",
     *     summary="Get related products",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function relatedProducts(Product $product)
    {
        $relatedProducts = new Collection();

        $relatedProducts = $product->relatedProducts()
            ->with(['media'])
            ->select(['products.id', 'products.name', 'products.slug', 'products.summary', 'products.view' , 'products.price'])
            ->get();

        if($relatedProducts->count() < 5){
            $vendorProducts = $product->vendors->flatMap(function($vendor) {
                return $vendor->products()
                    ->with(['media'])
                    ->select(['products.id', 'products.name', 'products.slug', 'products.summary', 'products.view' , 'products.price'])
                    ->take(5)
                    ->get();
            });
            
            $relatedProducts = $relatedProducts->merge($vendorProducts);
        }

        if($relatedProducts->count() < 5){
            $relatedProducts = $relatedProducts->merge(
                $product->category->products()
                ->with(['media'])
                ->select(['products.id', 'products.name', 'products.slug', 'products.summary', 'products.view' , 'products.price'])
                ->take(5)
                ->get()
            );
        }

        return ProductResource::collection($relatedProducts);
    }
}