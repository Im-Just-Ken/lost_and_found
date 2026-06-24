<?php

namespace App\Modules\MissingReport\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\MissingReport\Member\Repositories\MissingReportRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
use App\Modules\Items\Actions\SearchItemsByImageAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class MissingReportController extends Controller
{
        public function __construct(
    
        protected SearchItemsByImageAction $searchItemsByImageAction
    ) {}
public function index(MissingReportRepository $repo)
{
    $items = $repo->latest();

    return inertia('Admin/MissingReports/Index', [
        'items' => ReportedItemResource::collection($items)->resolve(),
        'imageSearch' => false,
    ]);
}

public function show(Item $item)
{
    if ($item->status !== ItemStatus::LOST) {
        return redirect()
            ->route('admin.reported_items.missing.index')
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

    return inertia('Admin/MissingReports/Show', [
        'item' => (new ReportedItemResource($item))->resolve(),
    ]);
}

public function destroy(Item $item)
{
    DB::transaction(function () use ($item) {

        foreach ($item->images as $image) {

            Storage::disk('public')->delete($image->path);

            $image->vector()?->delete();

            $image->delete();
        }

        $item->delete(); // Soft delete
    });

    return redirect()
        ->route('admin.reported_items.missing.index')
        ->with('success', 'Missing report deleted successfully.');
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

        return back()->with('success', 'Item marked as found.');
    }

public function searchByImage(
    Request $request,
    SearchItemsByImageAction $searchItemsByImageAction
) {
    $request->validate([
        'image' => ['required', 'image', 'max:10240'],
    ]);

    $items = $searchItemsByImageAction->execute(
        $request->file('image')
    );

    return inertia('Admin/MissingReports/Index', [
        'items' => ReportedItemResource::collection($items)->resolve(),
        'imageSearch' => true,
    ]);
}


}