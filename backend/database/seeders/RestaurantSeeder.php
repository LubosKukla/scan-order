<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Restaurant;
use App\Models\Restaurant_billing;
use App\Models\Type_kitchen;
use App\Models\Type_restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

//bola vygenerovana AI
class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type_restaurant::query()->firstOrCreate(['type' => 'Ostatné']);
        Type_kitchen::query()->firstOrCreate(['type' => 'Ostatné']);

        $restaurants = [
            [
                'user' => [
                    'email'    => 'contact@centralcafe.sk',
                    'phone'    => '+421901001001',
                    'password' => 'restaurant123',
                ],
                'profile' => [
                    'name'            => 'Central Cafe',
                    'name_boss'       => 'Eva Malakova',
                    'description'     => 'Male prijemne bistro v centre mesta.',
                    'number_of_tables' => 18,
                    'is_active'       => true,
                ],
                'address' => [
                    'street'             => 'Namestie SNP',
                    'number_of_building' => '1',
                    'PSC'                => '81101',
                    'city'               => 'Bratislava',
                ],
                'type' => 'Cafe',
                'kitchens' => ['Slovenska', 'International'],
            ],
            [
                'user' => [
                    'email'    => 'info@modryhlav.sk',
                    'phone'    => '+421902002002',
                    'password' => 'restaurant123',
                ],
                'profile' => [
                    'name'            => 'Modry Hlav',
                    'name_boss'       => 'Peter Kollar',
                    'description'     => 'Jednoduche obedove menu s domacou kuchynou.',
                    'number_of_tables' => 24,
                    'is_active'       => true,
                ],
                'address' => [
                    'street'             => 'Levocska',
                    'number_of_building' => '14',
                    'PSC'                => '08001',
                    'city'               => 'Presov',
                ],
                'type' => 'Bistro',
                'kitchens' => ['Slovenska'],
            ],
            [
                'user' => [
                    'email'    => 'rezervacie@riveg.sk',
                    'phone'    => '+421903003003',
                    'password' => 'restaurant123',
                ],
                'profile' => [
                    'name'            => 'River Garden',
                    'name_boss'       => 'Marek Kamen',
                    'description'     => 'Moderny podnik s vecernym menu a drinkami.',
                    'number_of_tables' => 30,
                    'is_active'       => false,
                ],
                'address' => [
                    'street'             => 'Nabrezna',
                    'number_of_building' => '7',
                    'PSC'                => '94901',
                    'city'               => 'Nitra',
                ],
                'type' => 'Restaurant',
                'kitchens' => ['International'],
            ],
            [
                'user' => [
                    'email'    => 'test@test.sk',
                    'phone'    => '+421904004004',
                    'password' => 'testtest',
                ],
                'profile' => [
                    'name'            => 'Test Restauracia',
                    'name_boss'       => 'Test Tester',
                    'description'     => 'Testovacia restauracia pre prihlasenie.',
                    'number_of_tables' => 10,
                    'is_active'       => true,
                    'other_restaurant_type' => 'Vlastny typ',
                    'other_type_kitchen' => 'Vlastna kuchyna',
                ],
                'address' => [
                    'street'             => 'Testovacia',
                    'number_of_building' => '10',
                    'PSC'                => '01001',
                    'city'               => 'Zilina',
                ],
                'type' => 'Test',
                'kitchens' => ['Slovenska'],
                'billing' => [
                    'company_name' => 'Test Restauracia s.r.o.',
                    'ico' => '12345678',
                    'dic' => '2012345678',
                    'ic_dph' => 'SK2012345678',
                    'iban' => 'SK991010051478454681561',
                    'bill_to_company' => true,
                    'billing_street' => 'Fakturacna 12',
                    'billing_city' => 'Zilina',
                    'billing_zip' => '01001',
                    'billing_country' => 'Slovensko',
                    'billing_email' => 'billing@test.sk',
                    'subscription_status' => 'trialing',
                    'trial_ends_at' => now()->addDays(14),
                ],
            ],
        ];

        foreach ($restaurants as $restaurantData) {
            $address = Address::query()->firstOrCreate($restaurantData['address']);

            $user = User::query()->updateOrCreate(
                ['email' => $restaurantData['user']['email']],
                [
                    'password'    => Hash::make($restaurantData['user']['password']),
                    'accept_gdpr' => true,
                    'phone'       => $restaurantData['user']['phone'],
                    'role'        => 'restaurant',
                ]
            );

            $type = Type_restaurant::query()->firstOrCreate(['type' => $restaurantData['type']]);

            $restaurant = Restaurant::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'is_active'          => $restaurantData['profile']['is_active'],
                    'name'               => $restaurantData['profile']['name'],
                    'name_boss'          => $restaurantData['profile']['name_boss'],
                    'type_restaurant_id' => $type->id,
                    'other_restaurant_type' => $restaurantData['profile']['other_restaurant_type'] ?? null,
                    'other_type_kitchen' => $restaurantData['profile']['other_type_kitchen'] ?? null,
                    'description'        => $restaurantData['profile']['description'],
                    'logo_path'          => null,
                    'address_id'         => $address->id,
                    'number_of_tables'   => $restaurantData['profile']['number_of_tables'],
                ]
            );

            if (! empty($restaurantData['kitchens'])) {
                $kitchenIds = [];

                foreach ($restaurantData['kitchens'] as $kitchenType) {
                    $kitchenIds[] = Type_kitchen::query()->firstOrCreate(['type' => $kitchenType])->id;
                }

                $restaurant->kitchens()->sync($kitchenIds);
            }

            if (! empty($restaurantData['billing'])) {
                $billing = $restaurantData['billing'];
                Restaurant_billing::query()->updateOrCreate(
                    ['restaurant_id' => $restaurant->id],
                    [
                        'company_name' => $billing['company_name'] ?? null,
                        'ico' => $billing['ico'] ?? null,
                        'dic' => $billing['dic'] ?? null,
                        'ic_dph' => $billing['ic_dph'] ?? null,
                        'iban' => $billing['iban'] ?? null,
                        'bill_to_company' => $billing['bill_to_company'] ?? false,
                        'billing_street' => $billing['billing_street'] ?? null,
                        'billing_city' => $billing['billing_city'] ?? null,
                        'billing_zip' => $billing['billing_zip'] ?? null,
                        'billing_country' => $billing['billing_country'] ?? null,
                        'billing_email' => $billing['billing_email'] ?? null,
                        'subscription_status' => $billing['subscription_status'] ?? null,
                        'trial_ends_at' => $billing['trial_ends_at'] ?? null,
                    ]
                );
            }
        }
    }
}
