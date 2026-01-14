<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Menu_item;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Database\Seeder;

//seed bol vygenerovaný AI
class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::query()->take(4)->get();
        $restaurant = Restaurant::query()
            ->whereHas('user', fn($query) => $query->where('email', 'test@test.sk'))
            ->first();

        if ($customers->isEmpty() || ! $restaurant) {
            return;
        }

        $menuItems = Menu_item::query()
            ->where('restaurant_id', $restaurant->id)
            ->get();

        if ($menuItems->isEmpty()) {
            $menuItemsData = [
                ['name' => 'Test Burger', 'type' => 'food', 'category' => 'Hlavné jedlá', 'base_price' => 8.90],
                ['name' => 'Test Salat', 'type' => 'food', 'category' => 'Saláty', 'base_price' => 6.50],
                ['name' => 'Test Limonada', 'type' => 'drink', 'category' => 'Nápoje', 'base_price' => 2.90],
            ];

            foreach ($menuItemsData as $item) {
                Menu_item::query()->firstOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'name' => $item['name'],
                    ],
                    [
                        'type' => $item['type'],
                        'category' => $item['category'],
                        'base_price' => $item['base_price'],
                        'is_active' => true,
                    ]
                );
            }

            $menuItems = Menu_item::query()
                ->where('restaurant_id', $restaurant->id)
                ->get();
        }

        $customer1 = $customers[0];
        $customer2 = $customers[1] ?? $customer1;
        $customer3 = $customers[2] ?? $customer1;
        $customer4 = $customers[3] ?? $customer1;

        $restaurantReview1 = Review::query()->create([
            'customer_id' => $customer1->id,
            'restaurant_id' => $restaurant->id,
            'menu_item_id' => null,
            'response_id' => null,
            'type' => 'restaurant',
            'text' => 'Velmi prijemna obsluha a ciste prostredie.',
            'rating' => 4.7,
        ]);

        Review::query()->create([
            'customer_id' => null,
            'restaurant_id' => $restaurantReview1->restaurant_id,
            'menu_item_id' => $restaurantReview1->menu_item_id,
            'response_id' => $restaurantReview1->id,
            'type' => $restaurantReview1->type,
            'text' => 'Dakujeme za navstevu a hodnotenie.',
            'rating' => $restaurantReview1->rating,
        ]);

        Review::query()->create([
            'customer_id' => $customer2->id,
            'restaurant_id' => $restaurant->id,
            'menu_item_id' => null,
            'response_id' => null,
            'type' => 'restaurant',
            'text' => 'Jedlo bolo chutne, prisiel by som znova.',
            'rating' => 4.4,
        ]);

        Review::query()->create([
            'customer_id' => $customer3->id,
            'restaurant_id' => $restaurant->id,
            'menu_item_id' => null,
            'response_id' => null,
            'type' => 'restaurant',
            'text' => 'Trochu dlhsie cakanie, ale super chut.',
            'rating' => 4.1,
        ]);

        $menuItem1 = $menuItems[0] ?? null;
        $menuItem2 = $menuItems[1] ?? null;
        $menuItem3 = $menuItems[2] ?? null;

        if ($menuItem1) {
            Review::query()->create([
                'customer_id' => $customer1->id,
                'restaurant_id' => $restaurant->id,
                'menu_item_id' => $menuItem1->id,
                'response_id' => null,
                'type' => 'menu_item',
                'text' => 'Burger bol fakt vyborny.',
                'rating' => 4.9,
            ]);
        }

        if ($menuItem2) {
            Review::query()->create([
                'customer_id' => $customer2->id,
                'restaurant_id' => $restaurant->id,
                'menu_item_id' => $menuItem2->id,
                'response_id' => null,
                'type' => 'menu_item',
                'text' => 'Salat bol cerstvy a lahky.',
                'rating' => 4.3,
            ]);
        }

        if ($menuItem3) {
            $menuReview = Review::query()->create([
                'customer_id' => $customer4->id,
                'restaurant_id' => $restaurant->id,
                'menu_item_id' => $menuItem3->id,
                'response_id' => null,
                'type' => 'menu_item',
                'text' => 'Limonada bola super osviezujuaca.',
                'rating' => 4.6,
            ]);

            Review::query()->create([
                'customer_id' => null,
                'restaurant_id' => $restaurant->id,
                'menu_item_id' => $menuItem3->id,
                'response_id' => $menuReview->id,
                'type' => $menuReview->type,
                'text' => 'Dakujeme, velmi nas to tesi.',
                'rating' => $menuReview->rating,
            ]);
        }
    }
}
