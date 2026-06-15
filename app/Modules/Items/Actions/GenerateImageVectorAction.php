<?php

namespace App\Modules\Items\Actions;

use App\Models\Shared\Item;
use App\Models\Shared\ItemImage;
use App\Models\Shared\ItemImageVector;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateImageVectorAction
{
    public function execute(Item $item, ItemImage $image): void
    {
        $absolutePath = Storage::disk('public')->path($image->path);

        if (!file_exists($absolutePath)) {
            throw new \Exception("Image not found");
        }

        /*STEP 1: Upload file to Gradio*/

        $upload = Http::attach(
            'files',
            file_get_contents($absolutePath),
            basename($absolutePath)
        )->post('https://kennethicus-siglip-embed-api.hf.space/gradio_api/upload');

        if (!$upload->successful()) {
            throw new \Exception('Upload failed: ' . $upload->body());
        }

        $uploadData = $upload->json();

        $uploadedPath = $uploadData[0]
            ?? $uploadData['files'][0]
            ?? $uploadData['data'][0]
            ?? null;

        if (!$uploadedPath) {
            throw new \Exception('Unexpected upload response: ' . json_encode($uploadData));
        }

        /*STEP 2: Call embed endpoint */

        $event = Http::post(
            'https://kennethicus-siglip-embed-api.hf.space/gradio_api/call/v2/embed',
            [
                'image' => [
                    'path' => $uploadedPath,
                    'meta' => [
                        '_type' => 'gradio.FileData'
                    ]
                ]
            ]
        );

        if (!$event->successful()) {
            throw new \Exception('SigLIP request failed: ' . $event->body());
        }

        $eventId = $event->json('event_id');

        /* STEP 3: Fetch SSE result*/

        $result = Http::get(
            "https://kennethicus-siglip-embed-api.hf.space/gradio_api/call/embed/{$eventId}"
        );

        if (!$result->successful()) {
            throw new \Exception('Failed to fetch embedding result: ' . $result->body());
        }

        $embedding = null;

        foreach (explode("\n", $result->body()) as $line) {
            if (str_starts_with($line, "data: ")) {
                $json = json_decode(substr($line, 6), true);

                if (isset($json[0])) {
                    $embedding = $json[0];
                }
            }
        }

        if (!$embedding) {
            throw new \Exception("Failed to extract embedding from SSE stream");
        }

        /* SAVE VECTOR */

        ItemImageVector::updateOrCreate(
            [
                'item_image_id' => $image->id,
            ],
            [
                'item_id' => $item->id,
                'embedding' => $embedding,
                'model' => 'siglip-base-patch16-224',
            ]
        );
    }
}