<?php

namespace App\Modules\Deleted\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportedItemResource;
use App\Modules\Deleted\Admin\Repositories\DeletedRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
class DeletedController extends Controller
{
    public function index(DeletedRepository $repo)
    {
        $items = $repo->latest();

        return inertia('Admin/Deleted/Index', [
            'items' => ReportedItemResource::collection($items)->resolve(),
        ]);
    } 

public function show(Item $item)
{
    if ($item->status !== ItemStatus::DELETED) {
        return redirect()
            ->route('admin.reported_items.deleted.index')
            ->with('error', 'This item is no longer a deleted item.');
    }

    $item->load([
        'images',
        'user',
        'latestHistory.user',
        'histories' => function ($query) {
            $query->with('user')->latest();
        },
    ]);

    return inertia('Admin/Deleted/Show', [
        'item' => (new ReportedItemResource($item))->resolve(),
        'authUserId' => Auth::id(),
    ]);
}


 
}