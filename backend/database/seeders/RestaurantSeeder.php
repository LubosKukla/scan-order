<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Restaurant;
use App\Models\Type_restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                    'number_of_tables'=> 18,
                    'is_active'       => true,
                ],
                'address' => [
                    'street'             => 'Namestie SNP',
                    'number_of_building' => '1',
                    'PSC'                => '81101',
                    'city'               => 'Bratislava',
                ],
                'type' => 'Cafe',
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
                    'number_of_tables'=> 24,
                    'is_active'       => true,
                ],
                'address' => [
                    'street'             => 'Levocska',
                    'number_of_building' => '14',
                    'PSC'                => '08001',
                    'city'               => 'Presov',
                ],
                'type' => 'Bistro',
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
                    'number_of_tables'=> 30,
                    'is_active'       => false,
                ],
                'address' => [
                    'street'             => 'Nabrezna',
                    'number_of_building' => '7',
                    'PSC'                => '94901',
                    'city'               => 'Nitra',
                ],
                'type' => 'Restaurant',
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

            Restaurant::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'is_active'          => $restaurantData['profile']['is_active'],
                    'name'               => $restaurantData['profile']['name'],
                    'name_boss'          => $restaurantData['profile']['name_boss'],
                    'type_restaurant_id' => $type->id,
                    'description'        => $restaurantData['profile']['description'],
                    'logo_path'          => null,
                    'address_id'         => $address->id,
                    'number_of_tables'   => $restaurantData['profile']['number_of_tables'],
                ]
            );
        }
    }
}
