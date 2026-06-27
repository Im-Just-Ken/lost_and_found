<?php

namespace App\Modules\Dashboard\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shared\Item;
use App\Enums\ItemStatus;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ItemResource;
use App\Http\Resources\FoundByMeResource;
use App\Modules\FoundByMe\Member\Repositories\FoundByMeRepository;
use App\Models\Shared\ItemHistory;
use App\Http\Resources\ItemHistoryResource;
use App\Enums\ItemHistoryActionType;

class DashboardController extends Controller
{
    public function __invoke(FoundByMeRepository $foundByMeRepo)
    {
        $userId = Auth::id();

        return inertia('Member/Dashboard/Index', [
            'currentUserId' => $userId,
            'stats' => [
                'lost_items' => Item::query()
        ->where('user_id', $userId)
        ->whereNot('status', ItemStatus::DELETED)
        ->count(),

                'found_items' => Item::query()
                    ->whereHas('latestHistory', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->whereIn('status', [
                        ItemStatus::FOUND_PENDING,
                        ItemStatus::FOUND,
                        ItemStatus::CLAIMED,
                    ])
                    ->count(),

                'pending_review' => Item::query()
                    ->whereHas('latestHistory', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->where('status', ItemStatus::FOUND_PENDING)
                    ->count(),

                'resolved_items' => Item::query()
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                            ->orWhereHas('latestHistory', function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            });
                    })
                    ->where('status', ItemStatus::CLAIMED)
                    ->count(),
            ],

            /*
            |--------------------------------------------------------------------------
            | My Lost Items
            |--------------------------------------------------------------------------
            */
         'lostItems' => ItemResource::collection(
                        Item::query()
                            ->with([
                                'images',
                                'primaryImage',
                                'latestHistory',
                            ])
                            ->where('user_id', $userId)
                            ->where('status', '!=', ItemStatus::DELETED)
                            ->latest()
                            ->take(5)
                            ->get()
                    )->resolve(),
  'notifications' => ItemHistoryResource::collection(
                Item::query()
                    ->with([
                        'histories.user:id,name',
                        'histories.item:id,title',
                    ])
                    ->get()
                    ->flatMap(function ($item) use ($userId) {

                        // Latest "Marked as Found" report made by this user.
                        $latestFound = $item->histories
                            ->where('action_type', ItemHistoryActionType::MARKED_FOUND)
                            ->where('user_id', $userId)
                            ->sortByDesc('id')
                            ->first();

                        if (! $latestFound) {
                            return collect();
                        }

                        // Build the notification timeline for THIS finder report only.
                        return collect([$latestFound])
                            ->merge(
                                $item->histories
                                    ->whereIn('action_type', [
                                        ItemHistoryActionType::FOUND_APPROVED,
                                        ItemHistoryActionType::FOUND_REJECTED,
                                        ItemHistoryActionType::FOUND_REVERTED_TO_PENDING,
                                        ItemHistoryActionType::CLAIMED,
                                        ItemHistoryActionType::CLAIMED_REVERTED_TO_PENDING,
                                    ])
                                    ->filter(function ($history) use ($latestFound) {
                                        return data_get(
                                            $history->meta,
                                            'finder_history_id'
                                        ) === $latestFound->id;
                                    })
                            );
                    })
                    ->sortByDesc('created_at')
                    ->take(10)
                    ->values()
            )->resolve(),
            /*
            |--------------------------------------------------------------------------
            | Items I Found
            |--------------------------------------------------------------------------
            */
               'foundItems' => FoundByMeResource::collection(
                $foundByMeRepo->latest()->take(5)
            )->resolve(),
        ]);
    }
}