<?php

namespace App\Modules\Items\Member\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Items\Services\ItemService;
use App\Http\Resources\ItemResource;
use App\Models\Shared\Item;
use Illuminate\Support\Facades\Auth;
use App\Modules\Items\Repositories\ItemRepository;

class RecoveredItemController extends Controller
{ 
    public function __construct(
        protected ItemService $serviceå
    ) {}



public function index(ItemRepository $repo)
{
    $items = $repo->getClaimedItemsForUser(Auth::id());

    return inertia('Member/RecoveredItems/Index', [
        'items' => ItemResource::collection($items)->resolve(),
    ]);
}


public function show(Item $item)
{
    return inertia('Member/RecoveredItems/Show', [
        'item' => (new ItemResource(
            $item->load([
                'images',
                'histories',
                'latestHistory',
            ])
        ))->resolve(),
    ]);
}




}