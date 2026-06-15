<?php
namespace App\Modules\Items\Actions;

use App\Models\Shared\Item;
use App\Models\Shared\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Shared\ItemImageVector;

class SyncItemImagesAction
{
    public function __construct(
    protected GenerateImageVectorAction $generateImageVectorAction,
) {}
    public function execute(Item $item, Request $request): void
    {
        /* REMOVE IMAGES */
        if ($request->filled('removed_images')) {
            $images = ItemImage::whereIn('id', $request->removed_images)->get();

            foreach ($images as $img) {

              ItemImageVector::where('item_image_id',$img->id)->delete();

                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        /* ADD NEW IMAGES */
        $newImages = [];

        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store("uploads/items/{$item->id}", 'public');

            $itemImage = ItemImage::create([
                'item_id' => $item->id,
                'path' => $path,
                'is_primary' => false,
            ]);

            $newImages[] = $itemImage;

            $this->generateImageVectorAction->execute(
                $item,
                $itemImage
            );
                
            }
        }

        /* RESET PRIMARY */
        ItemImage::where('item_id', $item->id)
            ->update(['is_primary' => false]);

        /* SET PRIMARY */
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