<?php

namespace App\Modules\VerifiedFound\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\VerifiedFound\Admin\Repositories\VerifiedFoundRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
class VerifiedFoundController extends Controller
{

public function index(VerifiedFoundRepository $repo)
{
    $items = $repo->latest();

    return inertia('Admin/VerifiedFound/Index', [
        'items' => ReportedItemResource::collection($items)->resolve(),
    ]);
} 

public function show(Item $item)
{
    if ($item->status !== ItemStatus::FOUND) {
        return redirect()
            ->route('admin.reported_items.found.index')
            ->with('error', 'This item is no longer a verified found item.');
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

    return inertia('Admin/VerifiedFound/Show', [
        'item' => (new ReportedItemResource($item))->resolve(),
    ]);
}

public function markAsClaimed(Item $item)
{
    if ($item->status !== ItemStatus::FOUND) {
        return redirect()
            ->route('admin.verified_found.show')
            ->withErrors([
                'item' => 'This item is not eligible to be marked as claimed.',
            ]);
    }

    $item->update([
        'status' => ItemStatus::CLAIMED,
        'resolved_at' => now(),
    ]);

    ItemHistory::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'action_type' => ItemHistoryActionType::CLAIMED,
        'notes' => 'Marked the item as claimed by the owner.',
        'meta' => [
            'previous_status' => ItemStatus::CLAIMED->value,
            'new_status' => ItemStatus::CLAIMED->value,
        ],
    ]);

    return redirect()
        ->route('admin.reported_items.claimed.show', $item)
        ->with('success', 'Item successfully marked as claimed.');
}

public function revertToFoundPending(Item $item)
{
    if ($item->status !== ItemStatus::FOUND) {
        return redirect()
            ->route('admin.reported_items.verified_found.index')
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
        'action_type' => ItemHistoryActionType::FOUND_REJECTED,
        'notes' => ' Reverted the verified found status back to pending verification.',
        'meta' => [
            'previous_status' => ItemStatus::FOUND->value,
            'new_status' => ItemStatus::FOUND_PENDING->value,
        ],
    ]);

   return redirect()
        ->route('admin.reported_items.pending_verification.show', $item)
        ->with('success', 'The item has been reverted back to pending verification.');
}

}