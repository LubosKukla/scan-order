<?php

namespace App\Http\Controllers;

use App\Models\Menu_item;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReview(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'menu_item_id' => ['nullable', 'integer', 'exists:menu_items,id', 'required_if:type,menu_item'],
            'type' => ['required', 'in:menu_item,restaurant'],
            'text' => ['required', 'string'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        $user = $request->user();
        $restaurantId = $data['restaurant_id'];
        $type = $data['type'];
        $menuItemId = $type === 'menu_item' ? $data['menu_item_id'] : null;

        if ($menuItemId) {
            $menuItem = Menu_item::query()->find($menuItemId);
            if (! $menuItem || (int) $menuItem->restaurant_id !== (int) $restaurantId) {
                return response()->json(['message' => 'Neplatné jedlo pre danú reštauráciu.'], 422);
            }
        }

        $review = Review::query()->create([
            'menu_item_id' => $menuItemId,
            'customer_id' => $user->customer->id,
            'restaurant_id' => $restaurantId,
            'response_id' => null,
            'type' => $type,
            'text' => $data['text'],
            'rating' => $data['rating'],
        ]);

        return response()->json(['review' => $review], 201);
    }

    public function respond(Request $request, Review $review)
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
        ]);

        $user = $request->user();
        $customerId = null;

        if ($review->response_id) {
            return response()->json(['message' => 'Na odpoveď nie je možné odpovedať.'], 422);
        }

        if ($user->isRestaurant()) {
            $ownsRestaurant = Restaurant::query()
                ->where('id', $review->restaurant_id)
                ->where('user_id', $user->id)
                ->exists();

            if (! $ownsRestaurant) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } elseif ($user->customer) {
            $customerId = $user->customer->id;
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $response = Review::query()->create([
            'menu_item_id' => $review->menu_item_id,
            'customer_id' => $customerId,
            'restaurant_id' => $review->restaurant_id,
            'response_id' => $review->id,
            'type' => $review->type,
            'text' => $data['text'],
            'rating' => $review->rating,
        ]);

        return response()->json(['review' => $response], 201);
    }

    public function deleteReview(Request $request, Review $review)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $ownsRestaurant = Restaurant::query()
            ->where('id', $review->restaurant_id)
            ->where('user_id', $user->id)
            ->exists();

        if (! $ownsRestaurant) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $review->responses()->delete();
        $review->delete();

        return response()->json(['message' => 'Recenzia bola vymazaná.']);
    }

    public function updateResponse(Request $request, Review $review)
    {
        $data = $request->validate([
            'text' => ['required', 'string'],
        ]);

        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $ownsRestaurant = Restaurant::query()
            ->where('id', $review->restaurant_id)
            ->where('user_id', $user->id)
            ->exists();

        if (! $ownsRestaurant || ! $review->response_id || $review->customer_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $review->update([
            'text' => $data['text'],
        ]);

        return response()->json(['message' => 'Recenzia bola upravená.', 'review' => $review]);
    }
}
