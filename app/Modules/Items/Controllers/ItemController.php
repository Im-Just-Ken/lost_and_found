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

use App\Modules\Items\Repositories\ItemRepository;

class ItemController extends Controller
{ 
    public function __construct(
        protected ItemService $service
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
        $items = $repo->latestForUser(auth()->id())
            ->load(['images']);

        return inertia('Member/Items/Index', [
            'items' => ItemResource::collection($items)->resolve(),
        ]);
    }


    public function edit(Item $item)
    {
        return inertia('Member/Items/Edit', [
            'item' => (new ItemResource(
                $item->load(['images'])
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

}