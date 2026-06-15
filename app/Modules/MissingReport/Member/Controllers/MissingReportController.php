<?php

namespace App\Modules\MissingReport\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\MissingReport\Member\Repositories\MissingReportRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\FoundItemPendingVerificationMail;
use App\Services\AdminNotificationService;

use App\Modules\Items\Actions\SearchItemsByImageAction;

class MissingReportController extends Controller
{

        public function __construct(
    
        protected SearchItemsByImageAction $searchItemsByImageAction
    ) {}
public function index(MissingReportRepository $repo)
{
    return inertia('Member/MissingReports/Index', [
        'items' => session(
            'imageSearchResults',
            ReportedItemResource::collection($repo->latest())->resolve()
        ),
        'imageSearch' => session('imageSearch', false),
        'searchImage' => session('searchImage'),
    ]);
}



    public function show(Item $item)
    {
        return inertia('Member/MissingReports/Show', [
            'item' => (new ReportedItemResource(
                $item->load(['images','user'])
            ))->resolve(), 
        ]);
    }

public function markAsFound(Request $request, Item $item)
{
    if ($item->status !== ItemStatus::LOST) {
        return back()->withErrors([
            'item' => 'This item is no longer available.',
        ]);
    }

    $item->update([
        'status' => ItemStatus::FOUND_PENDING,
    ]);

    ItemHistory::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'action_type' => ItemHistoryActionType::MARKED_FOUND,
        'notes' => 'A user reported this item as found.',
        'meta' => [
            'previous_status' => ItemStatus::LOST->value,
            'new_status' => ItemStatus::FOUND_PENDING->value,
            'reported_by_user_id' => Auth::id(),
        ],
    ]);

    AdminNotificationService::send(
        new FoundItemPendingVerificationMail(
            $item->load('user'),
            Auth::user()
        )
    );

    return redirect()->route(
        'member.community.found-by-me.show',
        $item
    )->with(
        'success',
        'Item marked as found.'
    );
}

public function searchByImage(Request $request)
{
    $request->validate([
        'image' => ['required', 'image', 'max:10240'],
    ]);

    $uploadedFile = $request->file('image');

    $items = $this->searchItemsByImageAction->execute($uploadedFile);

    return redirect()
        ->route('member.community.missing-reports.index')
        ->with([
            'imageSearchResults' => ReportedItemResource::collection($items)->resolve(),
            'imageSearch' => true,
            'searchImage' => $uploadedFile
                ? base64_encode(file_get_contents($uploadedFile->getRealPath()))
                : null,
        ]);
}
    
}