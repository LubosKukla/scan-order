<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Basket_item;
use App\Models\Customer;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function getBaskets($customerId)
    {
        $baskets = Basket::where('customer_id', $customerId)->get();
        return response()->json($baskets);
    }

    public function addItemToBasket(Request $request, Customer $customer, Restaurant $restaurant)
    {
        $customerId = $customer->id;

        $loginCustomer = auth()->user();

        if ($loginCustomer->id !== $customerId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $data = $request->validate([
            'menu_item_id'   => ['required', 'exists:menu_items,id'],
            'item_variant_id' => ['nullable', 'exists:item_variants,id'],
            'quantity'       => ['required', 'integer', 'min:1'],
            'price'          => ['required', 'numeric', 'min:0'],
            'note'           => ['nullable', 'string'],
        ]);

        $basket = Basket::firstOrCreate(
            [
                'customer_id'   => $customer->id,
                'restaurant_id' => $restaurant->id,
                'status'        => 'open',
            ],
            [
                'subtotal' => 0,
                'total'    => 0,
            ]
        );

        $basketItem = $basket->basketItems()->create([
            'menu_item_id'    => $data['menu_item_id'],
            'item_variant_id' => $data['item_variant_id'] ?? null,
            'quantity'        => $data['quantity'],
            'price'           => $data['price'],
            'note'            => $data['note'] ?? null,
        ]);

        $basket->update([
            'subtotal' => $basket->basketItems->sum(fn($item) => $item->quantity * $item->price),
            'total'    => $basket->subtotal,
        ]);

        return response()->json([
            'basket'      => $basket->load('basketItems'),
            'basket_item' => $basketItem,
        ], 201);
    }


    public function removeItemFromBasket(Customer $customer, Basket $basket, Basket_item $itemId)
    {
        $loginCustomer = auth()->user();

        if ($loginCustomer->id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($itemId->basket_id !== $basket->id) {
            return response()->json(['message' => 'Item does not belong to the specified basket'], 400);
        }

        $item = Basket_item::find($itemId->id);
        if (!$item) {
            return response()->json(['message' => 'Item not found in basket'], 404);
        }
        $item->delete();
        $basket->load('basketItems');
        $basket->update([
            'subtotal' => $basket->basketItems->sum(fn($item) => $item->quantity * $item->price),
            'total'    => $basket->subtotal,
        ]);
        return response()->json(['message' => 'Item removed from basket', 'basket' => $basket], 200);
    }

    public function updateItemInBasket(Request $request, Customer $customer, Basket $basket, Basket_item $item)
    {
        $loginCustomer = auth()->user();

        if ($loginCustomer->id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($item->basket_id !== $basket->id) {
            return response()->json(['message' => 'Item does not belong to the specified basket'], 400);
        }
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'price'    => ['required', 'numeric', 'min:0'],
            'note'     => ['nullable', 'string'],
        ]);

        $item->update([
            'quantity' => $data['quantity'],
            'price'    => $data['price'],
            'note'     => $data['note'] ?? $item->note,
        ]);

        $basket->load('basketItems');
        $basket->update([
            'subtotal' => $basket->basketItems->sum(fn($item) => $item->quantity * $item->price),
            'total'    => $basket->subtotal,
        ]);

        return response()->json([
            'message' => 'Item updated successfully',
            'basket'  => $basket,
            'item'    => $item,
        ], 200);
    }
}
