<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Type_kitchen;
use App\Models\Type_restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Nette\Utils\Type;

class RestaurantController extends Controller
{

    public function show(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $restaurant->loadMissing([
            'address',
            'typeRestaurant',
            'kitchens',
            'restaurantBilling.plan',
            'reviews',
            'user' => fn($query) => $query->select('id', 'email', 'phone'),
        ]);

        return response()->json([
            'restaurant' => array_merge(
                $restaurant->toArray(),
                [
                    'email' => optional($restaurant->user)->email,
                    'phone' => optional($restaurant->user)->phone,
                ],
            ),
        ]);
    }

    public function getTypes()
    {
        $types = Type_restaurant::all();
        if (! $types->firstWhere('type', 'Ostatné')) {
            $types->push(new Type_restaurant(['type' => 'Ostatné']));
        }
        return response()->json(['types' => $types]);
    }

    public function getKitchens()
    {
        $kitchens = Type_kitchen::all();
        if (! $kitchens->firstWhere('type', 'Ostatné')) {
            $kitchens->push(new Type_kitchen(['type' => 'Ostatné']));
        }
        return response()->json(['kitchens' => $kitchens]);
    }

    public function getSettings(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $restaurant->loadMissing([
            'address',
            'typeRestaurant',
            'kitchens',
            'restaurantBilling',
            'user' => fn($query) => $query->select('id', 'email', 'phone'),
        ]);

        $address = $restaurant->address;
        $billing = $restaurant->restaurantBilling;
        $kitchen = $restaurant->kitchens->first();

        $street = $address
            ? trim($address->street . ' ' . $address->number_of_building)
            : '';

        return response()->json([
            'profile' => [
                'name' => $restaurant->name ?? '',
                'owner' => $restaurant->name_boss ?? '',
                'email' => optional($restaurant->user)->email ?? '',
                'phone' => optional($restaurant->user)->phone ?? '',
                'restaurantType' => $restaurant->other_restaurant_type
                    ? 'Ostatné'
                    : (optional($restaurant->typeRestaurant)->type ?? ''),
                'otherRestaurantType' => $restaurant->other_restaurant_type ?? '',
                'cuisine' => $restaurant->other_type_kitchen
                    ? 'Ostatné'
                    : (optional($kitchen)->type ?? ''),
                'otherCuisine' => $restaurant->other_type_kitchen ?? '',
                'description' => $restaurant->description ?? '',
                'seats' => $restaurant->number_of_tables ?? 0,
                'logo' => $restaurant->logo_path ?? '',
                'isActive' => (bool) $restaurant->is_active,
                'temporaryClosed' => (bool) $restaurant->is_temporarily_closed,
                'address' => [
                    'street' => $street,
                    'city' => $address->city ?? '',
                    'zip' => $address->PSC ?? '',
                ],
                'billToCompany' => $billing ? (bool) $billing->bill_to_company : false,
                'company' => [
                    'name' => optional($billing)->company_name ?? '',
                    'ico' => optional($billing)->ico ?? '',
                    'dic' => optional($billing)->dic ?? '',
                    'icDph' => optional($billing)->ic_dph ?? '',
                ],
                'billing' => [
                    'iban' => optional($billing)->iban ?? '',
                    'street' => optional($billing)->billing_street ?? '',
                    'city' => optional($billing)->billing_city ?? '',
                    'zip' => optional($billing)->billing_zip ?? '',
                    'country' => optional($billing)->billing_country ?? '',
                    'email' => optional($billing)->billing_email ?? '',
                ],
            ],
        ]);
    }

    public function updateSettings(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255'],
            'owner' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'restaurantType' => ['nullable', 'string', 'max:255'],
            'otherRestaurantType' => ['nullable', 'string', 'max:255'],
            'cuisine' => ['nullable', 'string', 'max:255'],
            'otherCuisine' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'seats' => ['nullable', 'integer', 'min:0'],
            'logo' => ['nullable', 'string', 'max:255'],
            'billToCompany' => ['nullable', 'boolean'],
            'company.name' => ['nullable', 'string', 'max:255'],
            'company.ico' => ['nullable', 'string', 'max:50'],
            'company.dic' => ['nullable', 'string', 'max:50'],
            'company.icDph' => ['nullable', 'string', 'max:50'],
            'billing.iban' => ['nullable', 'string', 'max:50'],
            'billing.street' => ['nullable', 'string', 'max:255'],
            'billing.city' => ['nullable', 'string', 'max:255'],
            'billing.zip' => ['nullable', 'string', 'max:20'],
            'billing.country' => ['nullable', 'string', 'max:255'],
            'billing.email' => ['nullable', 'email', 'max:255'],
            'address.street' => ['nullable', 'string', 'max:255'],
            'address.city' => ['nullable', 'string', 'max:255'],
            'address.zip' => ['nullable', 'string', 'max:20'],
        ], [
            'email.email' => 'Zadajte platný email.',
            'email.unique' => 'Tento email už používa iný účet.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();

        $restaurantType = $data['restaurantType'] ?? null;
        if ($restaurantType) {
            if ($restaurantType === 'Ostatné') {
                $type = Type_restaurant::query()->firstOrCreate(['type' => 'Ostatné']);
                $restaurant->type_restaurant_id = $type->id;
                $restaurant->other_restaurant_type = $data['otherRestaurantType'] ?? null;
            } else {
                $type = Type_restaurant::query()->firstOrCreate(['type' => $restaurantType]);
                $restaurant->type_restaurant_id = $type->id;
                $restaurant->other_restaurant_type = null;
            }
        }

        $cuisine = $data['cuisine'] ?? null;
        if ($cuisine) {
            if ($cuisine === 'Ostatné') {
                $kitchen = Type_kitchen::query()->firstOrCreate(['type' => 'Ostatné']);
                $restaurant->other_type_kitchen = $data['otherCuisine'] ?? null;
            } else {
                $kitchen = Type_kitchen::query()->firstOrCreate(['type' => $cuisine]);
                $restaurant->other_type_kitchen = null;
            }

            $restaurant->kitchens()->sync([$kitchen->id]);
        }

        $logo = $data['logo'] ?? null;
        $restaurant->name = $data['name'] ?? $restaurant->name;
        $restaurant->name_boss = $data['owner'] ?? $restaurant->name_boss;
        $restaurant->description = $data['description'] ?? $restaurant->description;
        $restaurant->number_of_tables = $data['seats'] ?? $restaurant->number_of_tables;
        if (is_string($logo)) {
            $restaurant->logo_path = $logo;
        }

        $restaurant->save();

        if (! empty($data['email'])) {
            $user->email = $data['email'];
        }
        $user->phone = $data['phone'] ?? null;
        $user->save();

        $addressData = $data['address'] ?? [];
        $street = trim((string) ($addressData['street'] ?? ''));
        $city = trim((string) ($addressData['city'] ?? ''));
        $zip = trim((string) ($addressData['zip'] ?? ''));

        if ($street !== '' || $city !== '' || $zip !== '' || $restaurant->address) {
            $address = $restaurant->address ?: new Address();
            $address->street = $street ?: null;
            $address->number_of_building = null;
            $address->PSC = $zip ?: null;
            $address->city = $city ?: null;
            $address->save();

            $restaurant->address()->associate($address);
            $restaurant->save();
        }

        $billToCompany = (bool) ($data['billToCompany'] ?? false);
        $company = $data['company'] ?? [];
        $billing = $data['billing'] ?? [];

        $restaurant->restaurantBilling()->updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'company_name' => $billToCompany ? ($company['name'] ?? null) : null,
                'ico' => $billToCompany ? ($company['ico'] ?? null) : null,
                'dic' => $billToCompany ? ($company['dic'] ?? null) : null,
                'ic_dph' => $billToCompany ? ($company['icDph'] ?? null) : null,
                'iban' => $billing['iban'] ?? null,
                'bill_to_company' => $billToCompany,
                'billing_street' => $billing['street'] ?? null,
                'billing_city' => $billing['city'] ?? null,
                'billing_zip' => $billing['zip'] ?? null,
                'billing_country' => $billing['country'] ?? null,
                'billing_email' => $billing['email'] ?? null,
            ]
        );

        $restaurant->refresh();

        $response = $this->getSettings($restaurant);
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $payload = $response->getData(true);
        $payload['message'] = 'Nastavenia boli uložené.';

        return response()->json($payload);
    }

    public function getPublicReviews(Restaurant $restaurant)
    {
        $reviews = $restaurant->reviews()
            ->whereNull('response_id')
            ->with([
                'customer',
                'menuItem',
                'responses.customer',
            ])
            ->latest()
            ->get()
            ->map(function ($review) use ($restaurant) {
                $customer = $review->customer;
                $reviewer = $customer
                    ? trim($customer->name . ' ' . $customer->surname)
                    : 'Anonymous';

                $responses = $review->responses->map(function ($response) use ($restaurant) {
                    $responseCustomer = $response->customer;
                    $responseReviewer = $responseCustomer
                        ? trim($responseCustomer->name . ' ' . $responseCustomer->surname)
                        : $restaurant->name;

                    return [
                        'id' => $response->id,
                        'reviewer' => $responseReviewer,
                        'text' => $response->text,
                        'created_at' => $response->created_at,
                    ];
                });

                return [
                    'id' => $review->id,
                    'type' => $review->type,
                    'rating' => (float) $review->rating,
                    'text' => $review->text,
                    'created_at' => $review->created_at,
                    'reviewer' => $reviewer,
                    'menu_item' => $review->menuItem
                        ? ['id' => $review->menuItem->id, 'name' => $review->menuItem->name]
                        : null,
                    'responses' => $responses,
                ];
            })
            ->values();

        return response()->json(['reviews' => $reviews]);
    }

    public function setTemporaryClosure(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'closed' => ['required', 'boolean'],
        ], [
            'closed.required' => 'Chýba hodnota zatvorenia.',
        ]);

        $restaurant->is_temporarily_closed = (bool) $data['closed'];
        $restaurant->save();

        return response()->json([
            'message' => $restaurant->is_temporarily_closed
                ? 'Prevádzka je dočasne zatvorená.'
                : 'Prevádzka je otvorená.',
            'temporaryClosed' => (bool) $restaurant->is_temporarily_closed,
        ]);
    }

    public function deactivateAccount(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $restaurant->is_active = false;
        $restaurant->save();

        return response()->json(['message' => 'Účet bol deaktivovaný.']);
    }

    public function activateAccount(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $restaurant->is_active = true;
        $restaurant->save();

        return response()->json(['message' => 'Účet bol aktivovaný.']);
    }

    public function deleteAccount(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'restaurant_name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'restaurant_name.required' => 'Zadajte názov reštaurácie.',
            'password.required' => 'Zadajte heslo.',
        ]);

        if (trim($data['restaurant_name']) !== trim((string) $restaurant->name)) {
            return response()->json(['message' => 'Názov reštaurácie sa nezhoduje.'], 422);
        }

        if (! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Heslo nie je správne.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Účet bol trvalo odstránený.']);
    }

    public function getReviews(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $query = $restaurant->reviews()->whereNull('response_id');
        $this->applyReviewFilters($query, $request);

        $reviews = $query->with([
            'customer',
            'menuItem',
            'responses.customer',
        ])->latest()->get();

        return response()->json(['reviews' => $reviews]);
    }

    public function getReviewStats(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $baseQuery = Review::query()
            ->where('restaurant_id', $restaurant->id)
            ->whereNull('response_id');

        $type = $request->query('type');
        if (in_array($type, ['restaurant', 'menu_item'], true)) {
            $baseQuery->where('type', $type);
        }

        $total = (clone $baseQuery)->count();
        $average = (clone $baseQuery)->avg('rating') ?? 0;
        $monthStart = now()->startOfMonth();
        $monthCount = (clone $baseQuery)->whereBetween('created_at', [$monthStart, now()])->count();
        $responded = (clone $baseQuery)
            ->whereHas('responses', fn($query) => $query->whereNull('customer_id'))
            ->count();

        $stats = [
            'average_rating' => round((float) $average, 2),
            'total_reviews' => $total,
            'reviews_this_month' => $monthCount,
            'response_rate' => $total ? round(($responded / $total) * 100) : 0,
        ];

        //pomohlo AI pre zabezúpečenie len požadovaných štatistík, ak sú zadané v query parametri 'only' defaultne vráti všetky
        $only = array_filter(array_map('trim', explode(',', (string) $request->query('only'))));
        if (! empty($only)) {
            $stats = array_intersect_key($stats, array_flip($only));
        }

        return response()->json(['stats' => $stats]);
    }

    private function applyReviewFilters($query, Request $request): void
    {
        $type = $request->query('type');
        if (in_array($type, ['restaurant', 'menu_item'], true)) {
            $query->where('type', $type);
        }

        $rating = $request->query('rating');
        if ($rating === '2_or_less') {
            $query->where('rating', '<=', 2);
        } elseif (is_numeric($rating)) {
            $query->where('rating', '>=', (float) $rating);
        }

        $period = $request->query('period');
        if ($period && $period !== 'all') {
            $now = now();
            $start = null;
            $end = null;

            switch ($period) {
                case 'last_month':
                    $start = $now->copy()->subMonth();
                    $end = $now;
                    break;
                case 'last_quarter':
                    $start = $now->copy()->subMonths(3);
                    $end = $now;
                    break;
                case 'last_year':
                    $start = $now->copy()->subYear();
                    $end = $now;
                    break;
                case 'prev_month':
                    $start = $now->copy()->subMonth()->startOfMonth();
                    $end = $now->copy()->subMonth()->endOfMonth();
                    break;
                case 'prev_quarter':
                    $year = $now->year;
                    $currentQuarter = (int) ceil($now->month / 3);
                    $prevQuarter = $currentQuarter - 1;
                    if ($prevQuarter === 0) {
                        $prevQuarter = 4;
                        $year -= 1;
                    }
                    $startMonth = ($prevQuarter - 1) * 3 + 1;
                    $start = $now->copy()->setDate($year, $startMonth, 1)->startOfDay();
                    $end = $start->copy()->addMonths(2)->endOfMonth();
                    break;
                case 'prev_year':
                    $year = $now->year - 1;
                    $start = $now->copy()->setDate($year, 1, 1)->startOfDay();
                    $end = $now->copy()->setDate($year, 12, 31)->endOfDay();
                    break;
            }

            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%' . $search . '%';
            //pokročile prehladavanie podla textu, zakaznika menu pomohla AI
            //su to vnorene query ktore prehadaju tabulky reviews, customers a menu_items či sa text nenachádza v nich
            $query->where(function ($subQuery) use ($like) {
                $subQuery->where('text', 'like', $like)
                    ->orWhereHas('customer', function ($customerQuery) use ($like) {
                        $customerQuery->where('name', 'like', $like)
                            ->orWhere('surname', 'like', $like);
                    })
                    ->orWhereHas('menuItem', function ($menuQuery) use ($like) {
                        $menuQuery->where('name', 'like', $like);
                    });
            });
        }
    }

    public function getOpenHours(Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $openHours = $restaurant->openHours;

        return response()->json(['open_hours' => $openHours]);
    }

    public function setOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'open_hours' => ['required', 'array'],
            'open_hours.*.day_of_week' => ['required', 'integer', 'between:1,7'],
            'open_hours.*.open_time' => ['required', 'date_format:H:i'],
            'open_hours.*.close_time' => ['required', 'date_format:H:i'],
            'open_hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        foreach ($validatedData['open_hours'] as $hour) {
            $restaurant->openHours()->updateOrCreate(
                ['day_of_week' => $hour['day_of_week']],
                [
                    'open_time' => $hour['open_time'] ?? null,
                    'close_time' => $hour['close_time'] ?? null,
                    'is_closed' => $hour['is_closed']
                        ?? (empty($hour['open_time']) && empty($hour['close_time'])),
                ]
            );
        }

        return response()->json(['message' => 'Open hours updated successfully']);
    }

    public function updateOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'open_hours' => ['required', 'array', 'size:7'],
            'open_hours.*.day_of_week' => ['required', 'integer', 'between:1,7'],
            'open_hours.*.open_time' => ['nullable', 'date_format:H:i'],
            'open_hours.*.close_time' => ['nullable', 'date_format:H:i'],
            'open_hours.*.is_closed' => ['nullable', 'boolean'],
        ]);

        $normalizedHours = array_map(
            fn($hour) => [
                'day_of_week' => $hour['day_of_week'],
                'open_time' => $hour['open_time'] ?? null,
                'close_time' => $hour['close_time'] ?? null,
                'is_closed' => $hour['is_closed']
                    ?? (empty($hour['open_time']) && empty($hour['close_time'])),
            ],
            $validatedData['open_hours']
        );

        $restaurant->openHours()->delete();
        $restaurant->openHours()->createMany($normalizedHours);

        return response()->json(['message' => 'Open hours replaced successfully']);
    }

    public function deleteOpenHours(Request $request, Restaurant $restaurant)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($user->id !== $restaurant->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $restaurant->openHours()->delete();

        return response()->json(['message' => 'All open hours deleted successfully']);
    }
}
