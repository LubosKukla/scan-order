<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Type_kitchen;
use App\Models\Type_restaurant;
use Illuminate\Http\Request;
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
        return response()->json(['types' => $types]);
    }

    public function getKitchens()
    {
        $kitchens = Type_kitchen::all();
        return response()->json(['kitchens' => $kitchens]);
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
