<?php

namespace App\Modules\FoundByMe\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoundByMeResource;
use App\Modules\FoundByMe\Member\Repositories\FoundByMeRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
class FoundByMeController extends Controller
{
    public function index(FoundByMeRepository $repo)
    {
        $items = $repo->latest();

        return inertia('Member/FoundByMe/Index', [
            'items' => FoundByMeResource::collection($items)->resolve(),
        ]);
    } 

    public function show(Item $item)
    {
        return inertia('Member/FoundByMe/Show', [
            'item' => (new FoundByMeResource(
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

        return back()->with('success', 'Item marked as found.');
    }
}