<?php

namespace Database\Seeders;

use App\Models\Menu_item;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $menuByRestaurant = [
            'Central Cafe' => [
                [
                    'name'            => 'Avokádový toast',
                    'type'            => 'food',
                    'description'     => 'Chrumkavý toast s avokádom, vajíčkom a cherry paradajkami.',
                    'base_price'      => 6.50,
                    'time_to_prepare' => '00:10:00',
                    'weight'          => '220 g',
                    'kcal'            => 410,
                    'category'        => 'Raňajky',
                ],
                [
                    'name'            => 'Flat White',
                    'type'            => 'drink',
                    'description'     => 'Dvojité espresso s mikropenou.',
                    'base_price'      => 2.90,
                    'time_to_prepare' => '00:03:00',
                    'weight'          => '200 ml',
                    'kcal'            => 120,
                    'category'        => 'Káva',
                ],
            ],
            'Modry Hlav' => [
                [
                    'name'            => 'Hovädzí burger',
                    'type'            => 'food',
                    'description'     => '100% hovädzie mäso, čedar, slanina, BBQ omáčka.',
                    'base_price'      => 8.20,
                    'time_to_prepare' => '00:15:00',
                    'weight'          => '320 g',
                    'kcal'            => 730,
                    'category'        => 'Hlavné jedlá',
                ],
                [
                    'name'            => 'Šalát s kozím syrom',
                    'type'            => 'food',
                    'description'     => 'Mix listových šalátov, kozí syr, orechy, medovo-horčičný dressing.',
                    'base_price'      => 7.40,
                    'time_to_prepare' => '00:08:00',
                    'weight'          => '280 g',
                    'kcal'            => 540,
                    'category'        => 'Šaláty',
                ],
            ],
            'River Garden' => [
                [
                    'name'            => 'Losos s bylinkovou krustou',
                    'type'            => 'food',
                    'description'     => 'Pečený losos, batatové pyré, grilovaná zelenina.',
                    'base_price'      => 13.90,
                    'time_to_prepare' => '00:18:00',
                    'weight'          => '350 g',
                    'kcal'            => 620,
                    'category'        => 'Večera',
                ],
                [
                    'name'            => 'Signature Negroni',
                    'type'            => 'drink',
                    'description'     => 'Gin, Campari, červený vermút s twistom pomaranča.',
                    'base_price'      => 6.80,
                    'time_to_prepare' => '00:02:30',
                    'weight'          => '120 ml',
                    'kcal'            => 190,
                    'category'        => 'Koktail',
                ],
            ],
        ];

        foreach ($menuByRestaurant as $restaurantName => $items) {
            $restaurant = Restaurant::query()->where('name', $restaurantName)->first();

            if (! $restaurant) {
                $this->command?->warn("Preskakujem {$restaurantName} – reštaurácia neexistuje.");
                continue;
            }

            foreach ($items as $item) {
                Menu_item::query()->updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'name'          => $item['name'],
                    ],
                    [
                        'type'            => $item['type'],
                        'description'     => $item['description'],
                        'base_price'      => $item['base_price'],
                        'time_to_prepare' => $item['time_to_prepare'],
                        'weight'          => $item['weight'],
                        'kcal'            => $item['kcal'],
                        'category'        => $item['category'],
                        'is_active'       => true,
                    ]
                );
            }
        }
    }
}
