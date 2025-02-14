<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for categories"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="List all root product categories",
     *     description="Returns a list of all root-level categories with their media",
     *     operationId="listCategories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="media", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="url", type="string")
     *                 ))
     *             )
     *         )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::root()->with('media')->select(['id', 'name' , 'slug'])->get()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     summary="Get specific product category",
     *     description="Returns a specific category with its children and media and attributes",
     *     operationId="showCategory",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category slug",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Electronics"),
     *             @OA\Property(property="media", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="url", type="string")
     *             )),
     *             @OA\Property(property="children", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product category not found"
     *     )
     * )
     */
    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make(
            $category->load(['media', 'attributes.media' , 'children.media'])
        );
    }
}