<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ReviewResource",
 *     title="Review Resource",
 *     description="Review resource representation",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="Review ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/UserResource",
 *         description="The user who wrote the review"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         ref="#/components/schemas/ProductResource",
 *         description="The product being reviewed"
 *     ),
 *     @OA\Property(
 *         property="vendor",
 *         ref="#/components/schemas/VendorResource",
 *         description="The vendor being reviewed"
 *     ),
 *     @OA\Property(
 *         property="rating",
 *         type="integer",
 *         minimum=1,
 *         maximum=5,
 *         description="Rating score (1-5)",
 *         example=4
 *     ),
 *     @OA\Property(
 *         property="comment",
 *         type="string",
 *         description="Review comment",
 *         example="Great product, excellent service!"
 *     )
 * )
 */
class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this->id , $this->id),
            'user' => UserResource::make($this->whenLoaded('user')),
            'product' => ProductResource::make($this->whenLoaded('product')),
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'rating' => $this->when($this->rating , $this->rating),
            'comment' => $this->when($this->comment , $this->comment),
        ];
    }
}