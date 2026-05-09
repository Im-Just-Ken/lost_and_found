<?php
namespace App\Modules\Items\Actions;

use App\Models\Shared\ItemImage;
use App\Models\Shared\Item;
use Illuminate\Http\Request;
class StoreItemImagesAction
{
    public function execute(Item $item, Request $request): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $primaryIndex = $request->input('primary_index', 0);

        foreach ($request->file('images') as $index => $file) {

            $path = $file->store(
                "uploads/items/{$item->id}",
                'public'
            );

            ItemImage::create([
                'item_id' => $item->id,
                'path' => $path,
                'is_primary' => $index === (int) $primaryIndex,
            ]);
        }
    }
}