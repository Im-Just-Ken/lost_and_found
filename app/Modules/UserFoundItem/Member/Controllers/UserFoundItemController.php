<?php

namespace App\Modules\UserFoundItem\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFoundItemResource;
use App\Modules\UserFoundItem\Member\Repositories\UserFoundItemRepository;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Models\Shared\ItemHistory;
use App\Enums\ItemHistoryActionType;
use Illuminate\Support\Facades\Auth;
class UserFoundItemController extends Controller
{
    public function index(UserFoundItemRepository $repo)
    {
        $items = $repo->latest();

        return inertia('Member/UserFoundItems/Index', [
            'items' => UserFoundItemResource::collection($items)->resolve(),
        ]);
    } 

    public function show(Item $item)
    {
        return inertia('Member/UserFoundItems/Show', [
            'item' => (new UserFoundItemResource(
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