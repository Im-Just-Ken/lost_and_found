<?php

namespace App\Modules\PendingVerification\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\PendingVerification\Admin\Repositories\PendingVerificationRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;

use App\Services\FinderNotificationService;
use App\Services\EmailNotificationService;
use App\Mail\FoundReportApprovedMail;
use App\Mail\FoundReportRejectedMail;
use App\Mail\ItemFoundOwnerNotificationMail;

class PendingVerificationController extends Controller
{

public function index(PendingVerificationRepository $repo)
{
    $items = $repo->latest();

    return inertia('Admin/PendingVerification/Index', [
        'items' => ReportedItemResource::collection($items)->resolve(),
    ]);
} 

public function show(Item $item)
{
    if ($item->status !== ItemStatus::FOUND_PENDING) {
        return redirect()
            ->route('admin.reported_items.pending_verification.index')
            ->with('error', 'This item is no longer pending verification.');
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

    return inertia('Admin/PendingVerification/Show', [
        'item' => (new ReportedItemResource($item))->resolve(),
    ]);
}

public function approveFound(Request $request, Item $item)
{
  
    if ($item->status !== ItemStatus::FOUND_PENDING) {
        return back()->withErrors([
            'item' => 'This item is no longer eligible for found marking.',
        ]);
    }

    // 2. Update status
    $item->update([
        'status' => ItemStatus::FOUND,
    ]);

  
    $item->histories()->create([
        'user_id' => Auth::id(),
        'action_type' => ItemHistoryActionType::FOUND_APPROVED,
        'notes' => 'Marked as found verified.',
        'meta' => [
            'previous_status' => ItemStatus::FOUND_PENDING->value,
            'new_status' => ItemStatus::FOUND->value,
            'action_source' => 'admin_panel',
        ],
    ]);

    // Member finder email
    FinderNotificationService::sendToLatestFinder(
        $item,
        fn ($finder) => new FoundReportApprovedMail(
            $item,
            $finder
        )
    );

    // Owner email
    if ($item->user) {

        EmailNotificationService::sendToUser(
            $item->user,
            new ItemFoundOwnerNotificationMail(
                $item,
                $item->user
            )
        );
    }

      return redirect()
        ->route('admin.reported_items.found.show', $item)
        ->with('success', 'Item marked as found verified.');
}

public function disapproveFound(Item $item)
{
    if ($item->status !== ItemStatus::FOUND_PENDING) {
        return redirect()
            ->route('admin.pending_verification.index')
            ->with('error', 'This item is no longer pending verification.');
    }

    $latestFoundReport = $item->histories()
        ->where('action_type', ItemHistoryActionType::MARKED_FOUND)
        ->latest()
        ->first();

    $item->update([
        'status' => ItemStatus::LOST,
        'resolved_at' => null,
    ]);

    ItemHistory::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'action_type' => ItemHistoryActionType::FOUND_REJECTED,
        'notes' => 'Found report was rejected.',
        'meta' => [
            'previous_status' => ItemStatus::FOUND_PENDING->value,
            'new_status' => ItemStatus::LOST->value,

           
            'reviewed_by_user_id' => Auth::id(),

            // Finder whose report was rejected
            'finder_user_id' => $latestFoundReport?->user_id,
            'finder_history_id' => $latestFoundReport?->id,
        ],
    ]);

        FinderNotificationService::sendToLatestFinder(
        $item,
        fn ($finder) => new FoundReportRejectedMail(
            $item,
            $finder
        )
    );

    return redirect()
        ->route('admin.reported_items.missing.show', $item)
        ->with('success', 'Found report disapproved successfully.');
}
}