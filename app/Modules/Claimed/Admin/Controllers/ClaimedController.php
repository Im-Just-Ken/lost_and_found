<?php

namespace App\Modules\Claimed\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\Claimed\Admin\Repositories\ClaimedRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
class ClaimedController extends Controller
{
    public function index(ClaimedRepository $repo)
    {
        $items = $repo->latest();

        return inertia('Admin/Claimed/Index', [
            'items' => ReportedItemResource::collection($items)->resolve(),
        ]);
    } 

public function show(Item $item)
{
    if ($item->status !== ItemStatus::CLAIMED) {
        return redirect()
            ->route('admin.reported_items.claimed.index')
            ->with('error', 'This item is no longer a claimed item.');
    }

    $item->load([
        'images',
        'user',
        'latestHistory.user',
        'histories' => function ($query) {
            $query->with('user')
                ->latest();
        },
    ]);

    return inertia('Admin/Claimed/Show', [
        'item' => (new ReportedItemResource($item))->resolve(),
    ]);
}



public function revertToFoundPending(Item $item)
{
    if ($item->status !== ItemStatus::CLAIMED) {
        return redirect()
            ->route('admin.reported_items.pending_verification.index')
            ->withErrors([
                'item' => 'This item cannot be reverted.',
            ]);
    }

    $item->update([
        'status' => ItemStatus::FOUND_PENDING,
        'resolved_at' => null,
    ]);

    ItemHistory::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'action_type' => ItemHistoryActionType::CLAIMED_REVERTED_TO_PENDING,
        'notes' => ' Reverted the claimed status back to pending verification.',
        'meta' => [
            'previous_status' => ItemStatus::CLAIMED->value,
            'new_status' => ItemStatus::FOUND_PENDING->value,
        ],
    ]);

   return redirect()
        ->route('admin.reported_items.pending_verification.show', $item)
        ->with('success', 'The item has been reverted back to pending verification.');
}



 
}