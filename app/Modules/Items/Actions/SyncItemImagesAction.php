<?php
namespace App\Modules\Items\Actions;

use App\Models\Shared\Item;
use App\Models\Shared\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SyncItemImagesAction
{
    public function execute(Item $item, Request $request): void
    {
        /**
         * =========================
         * 1. REMOVE IMAGES
         * =========================
         */
        if ($request->filled('removed_images')) {
            $images = ItemImage::whereIn('id', $request->removed_images)->get();

            foreach ($images as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        /**
         * =========================
         * 2. ADD NEW IMAGES
         * =========================
         */
        $newImages = [];

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store("uploads/items/{$item->id}", 'public');

                $newImages[] = ItemImage::create([
                    'item_id' => $item->id,
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        /**
         * =========================
         * 3. RESET PRIMARY
         * =========================
         */
        ItemImage::where('item_id', $item->id)
            ->update(['is_primary' => false]);

        /**
         * =========================
         * 4. SET PRIMARY
         * =========================
         */
        if ($request->primary_type === 'existing') {
            $existing = $item->images()->get();

            if (isset($existing[$request->primary_index])) {
                $existing[$request->primary_index]->update([
                    'is_primary' => true,
                ]);
            }
        }

        if ($request->primary_type === 'new') {
            if (isset($newImages[$request->primary_index])) {
                $newImages[$request->primary_index]->update([
                    'is_primary' => true,
                ]);
            }
        }
    }
}