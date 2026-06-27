<?php

namespace App\Modules\Items\Controllers;

use App\Modules\Items\Requests\StoreItemRequest;
use App\Http\Controllers\Controller;
use App\Modules\Items\DTO\ItemData;
use App\Modules\Items\Services\ItemService;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\DB;
use App\Models\Shared\Item;
use App\Modules\Items\Requests\UpdateItemRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Enums\ItemStatus;
use App\Modules\Items\Repositories\ItemRepository;

use App\Enums\ItemHistoryActionType;
use App\Models\Shared\ItemHistory;


class ItemController extends Controller
{ 
    public function __construct(
        protected ItemService $service,
      
    ) {}

    
    public function store(StoreItemRequest $request)
    {
        $dto = ItemData::fromArray($request->validated());

        DB::transaction(function () use ($dto, $request) {
            $this->service->store($dto, $request);
        });

        return redirect()
            ->route('member.items.index')
            ->with('success', 'Item reported successfully.');
    }


     public function create()
    {
        return inertia('Member/Items/Create');
    }


public function index(ItemRepository $repo)
{
    $items = $repo->getMissingItemsForUser(Auth::id());

    return inertia('Member/Items/Index', [
        'items' => ItemResource::collection($items)->resolve(),
    ]);
}

public function edit(Item $item)
{
    return inertia('Member/Items/Edit', [
        'item' => (new ItemResource(
            $item->load([
                'images',
                'histories',
                'latestHistory',
            ])
        ))->resolve(),
    ]);
}


public function update(UpdateItemRequest $request, Item $item)
{
    $dto = ItemData::fromArray($request->validated());

    DB::transaction(function () use ($dto, $request, $item) {
        $this->service->update($item, $dto, $request);
    });

    return back()->with('success', 'Item updated successfully');
}



public function destroy(Request $request, Item $item)
{
    abort_if($item->user_id !== Auth::id(), 403);

    abort_if(
        $item->status !== ItemStatus::LOST,
        403,
        'Only active lost items can be deleted.'
    );

    $validated = $request->validate([
        'comment' => ['required', 'string', 'max:1000'],
    ]);

    DB::transaction(function () use ($item, $validated) {

        // Update status instead of deleting
        $item->update([
            'status'  => ItemStatus::DELETED,
            'comment' => $validated['comment'],
        ]);

        // Record history
        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'action_type' => ItemHistoryActionType::DELETED,
            'notes' => 'Owner deleted the item report.',
            'meta' => [
                'previous_status' => ItemStatus::LOST->value,
                'new_status'      => ItemStatus::DELETED->value,
                'comment'         => $validated['comment'],
            ],
        ]);

        // Remove all image embeddings
        // ItemImageVector::where('item_id', $item->id)->delete();
    });

    return redirect()
        ->route('member.items.index')
        ->with('success', 'Item deleted successfully.');
}
}