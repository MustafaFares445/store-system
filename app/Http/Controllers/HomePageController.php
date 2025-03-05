<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="HomePage",
 *     description="API Endpoints for HomePage"
 * )
 */
class HomePageController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/home/popular/categories/products",
     *     summary="Get popular categories and their products for the homepage",
     *     description="Retrieves root categories and their most viewed products, including products from child categories",
     *     operationId="getPopularCategoriesProducts",
     *     tags={"HomePage"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\AdditionalProperties(
     *                 type="array",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductResource"
     *                 ),
     *                 description="Array of products keyed by category slug"
     *             ),
     *             example={
     *                 "electronics": {
     *                     {
     *                         "id": 1,
     *                         "name": "Smartphone",
     *                         "slug": "smartphone",
     *                         "summary": "Latest smartphone model",
     *                         "view": 1500,
     *                         "price": 999.99
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Internal server error")
     *         )
     *     )
     * )
     */
    public function popularCategoriesProducts(): JsonResponse
    {
        // Eager load children along with media and attributes for all root categories.
        $categories = Category::root()
            ->select(['id' , 'slug'])
            ->with('children')
            ->get();

        $data = [];
        foreach ($categories as $category) {
            // use the preloaded children relation.
            $childIds = $category->children->pluck('id')->toArray();
            // Merge parent ID with its children IDs.
            $ids = array_merge([$category->id], $childIds);

            $products = Product::with('media')
                ->select(['id', 'name', 'slug', 'summary', 'view' , 'price'])
                ->whereHas('category', function (Builder $query) use ($ids) {
                    $query->whereIn('category_id', $ids);
                })
                ->orderByDesc('view')
                ->limit(15)
                ->get();


            $data[$category->slug][] = ProductResource::collection($products->shuffle());
        }

        return response()->json(['data' => $data]);
    }

    /**
     * @OA\Get(
     *     path="/api/home/popular/categories/{category}/products",
     *     summary="Get popular products from subcategories of a specific category",
     *     description="Retrieves up to 15 most viewed products from each subcategory, shuffled for display",
     *     tags={"HomePage"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category slug or ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="category_slug",
     *                 type="array",
     *                 description="Array of products for each category (key is category slug)",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductResource",
     *                     description="Product information including id, name, slug, summary, and view count"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Category not found")
     *         )
     *     )
     * )
     */
    public function popularCategoriesChildrenProducts(Category $category): JsonResponse
    {
        $data = [];
        foreach ($category->children as $children) {
            $products = Product::with('media')
                ->select(['id', 'name', 'slug', 'summary', 'view' , 'price'])
                ->whereHas('category', function (Builder $query) use ($children) {
                    $query->where('category_id', $children->id);
                })
                ->orderByDesc('view')
                ->limit(15)
                ->get();

            $data[$category->slug] = ProductResource::collection($products->shuffle());
        }

        return response()->json(['data' => $data]);
    }
}