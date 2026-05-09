<?php

namespace App\Modules\Items\Services;

use App\Modules\Items\DTO\ItemData;
use App\Modules\Items\Actions\CreateItemAction;
use App\Modules\Items\Actions\StoreItemImagesAction;
use App\Modules\Items\Actions\CreateItemHistoryAction;
use App\Modules\Items\Actions\UpdateItemAction;
use App\Modules\Items\Actions\SyncItemImagesAction;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
class ItemService
{
    public function __construct(
        protected CreateItemAction $createItemAction,
        protected StoreItemImagesAction $storeImagesAction,
        protected CreateItemHistoryAction $historyAction,
        protected UpdateItemAction $updateItemAction,
        protected SyncItemImagesAction $syncImagesAction,
    ) {}

    public function store(ItemData $dto, Request $request)
    {
        // 1. Create item
        $item = $this->createItemAction->execute($dto);

        // 2. Store images
        $this->storeImagesAction->execute($item, $request);

        // 3. Log history
        $this->historyAction->created($item, $dto);

        return $item;
    }

      public function update(Item $item, ItemData $dto, Request $request)
    {
        // 1. Update base item
        $this->updateItemAction->execute($item, $dto);

        // 2. Sync images
        $this->syncImagesAction->execute($item, $request);

        // 3. History
        $this->historyAction->updated($item, $dto);

        return $item;
    }
}