<?php

namespace App\Modules\Dashboard\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shared\Item;
use App\Models\User;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReportedItemResource;

class DashboardController extends Controller
{
  public function __invoke()
{
    return inertia('Admin/Dashboard/Index', [

        'stats' => [
            'members' => User::role('member')->count(),

            'missing' => [
                'total' => Item::where('status', ItemStatus::LOST)->count(),
                'week' => Item::where('status', ItemStatus::LOST)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],

            'pending_review' => [
                'total' => Item::where('status', ItemStatus::FOUND_PENDING)->count(),
                'week' => Item::where('status', ItemStatus::FOUND_PENDING)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],

            'found' => [
                'total' => Item::where('status', ItemStatus::FOUND)->count(),
                'week' => Item::where('status', ItemStatus::FOUND)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],

            'claimed' => [
                'total' => Item::where('status', ItemStatus::CLAIMED)->count(),
                'week' => Item::where('status', ItemStatus::CLAIMED)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],
        ],

        'statusChart' => [
            [
                'status' => 'Missing',
                'count' => Item::where('status', ItemStatus::LOST)->count(),
            ],
            [
                'status' => 'Pending Review',
                'count' => Item::where('status', ItemStatus::FOUND_PENDING)->count(),
            ],
            [
                'status' => 'Found',
                'count' => Item::where('status', ItemStatus::FOUND)->count(),
            ],
            [
                'status' => 'Claimed',
                'count' => Item::where('status', ItemStatus::CLAIMED)->count(),
            ],
        ],
        'authUser' => Auth::user(),
        'lostItems' => ReportedItemResource::collection(
        Item::query()
            ->with(['user', 'images', 'primaryImage'])
            ->where('status', ItemStatus::LOST)
            ->latest()
            ->take(20)
            ->get()
            )->resolve(),
        'pendingItems' => ReportedItemResource::collection(
            Item::query()
                ->with([
                    'user',
                    'images',
                    'primaryImage',
                    'histories.user',
                ])
                ->where('status', ItemStatus::FOUND_PENDING)
                ->latest()
                ->take(5)
                ->get()
        )->resolve(),
        'recentActivity' => ItemHistory::query()
            ->with([
                'item',
                'user.roles',
            ])
    ->latest()
    ->take(20)
    ->get(),
    ]);
}
}