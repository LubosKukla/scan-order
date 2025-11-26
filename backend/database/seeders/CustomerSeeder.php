<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'user' => [
                    'email'    => 'anna.customer@example.com',
                    'phone'    => '+421900000001',
                    'password' => 'customer123',
                ],
                'profile' => [
                    'name'    => 'Anna',
                    'surname' => 'Novakova',
                ],
                'address' => [
                    'street'             => 'Hlavna',
                    'number_of_building' => '12',
                    'PSC'                => '81101',
                    'city'               => 'Bratislava',
                ],
            ],
            [
                'user' => [
                    'email'    => 'brano.customer@example.com',
                    'phone'    => '+421900000002',
                    'password' => 'customer123',
                ],
                'profile' => [
                    'name'    => 'Branislav',
                    'surname' => 'Hrivnak',
                ],
                'address' => [
                    'street'             => 'Karpatska',
                    'number_of_building' => '5',
                    'PSC'                => '97401',
                    'city'               => 'Banska Bystrica',
                ],
            ],
            [
                'user' => [
                    'email'    => 'lena.customer@example.com',
                    'phone'    => '+421900000003',
                    'password' => 'customer123',
                ],
                'profile' => [
                    'name'    => 'Elena',
                    'surname' => 'Turkova',
                ],
                'address' => [
                    'street'             => 'Dunajska',
                    'number_of_building' => '21A',
                    'PSC'                => '04001',
                    'city'               => 'Kosice',
                ],
            ],
            [
                'user' => [
                    'email'    => 'marek.customer@example.com',
                    'phone'    => '+421900000004',
                    'password' => 'customer123',
                ],
                'profile' => [
                    'name'    => 'Marek',
                    'surname' => 'Sokol',
                ],
                'address' => [
                    'street'             => 'Okruzna',
                    'number_of_building' => '3',
                    'PSC'                => '94901',
                    'city'               => 'Nitra',
                ],
            ],
            [
                'user' => [
                    'email'    => 'sara.customer@example.com',
                    'phone'    => '+421900000005',
                    'password' => 'customer123',
                ],
                'profile' => [
                    'name'    => 'Sara',
                    'surname' => 'Homolova',
                ],
                'address' => [
                    'street'             => 'Ruzova',
                    'number_of_building' => '8',
                    'PSC'                => '01001',
                    'city'               => 'Zilina',
                ],
            ],
        ];

        foreach ($customers as $customerData) {
            $address = Address::query()->firstOrCreate($customerData['address']);

            $user = User::query()->updateOrCreate(
                ['email' => $customerData['user']['email']],
                [
                    'password'    => Hash::make($customerData['user']['password']),
                    'accept_gdpr' => true,
                    'phone'       => $customerData['user']['phone'],
                    'role'        => 'customer',
                ]
            );

            Customer::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'address_id' => $address->id,
                    'name'       => $customerData['profile']['name'],
                    'surname'    => $customerData['profile']['surname'],
                ]
            );
        }
    }
}
