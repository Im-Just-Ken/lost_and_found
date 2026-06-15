<?php

namespace App\Modules\Items\Actions;

use App\Models\Shared\ItemImageVector;
use Illuminate\Http\UploadedFile;

class SearchItemsByImageAction
{
    public function __construct(
        protected GenerateTemporaryImageVectorAction $generateTemporaryImageVectorAction
    ) {}

    public function execute(UploadedFile $image)
    {
        $queryVector = $this->generateTemporaryImageVectorAction
            ->execute($image);

        $matches = ItemImageVector::with([
                'image',
                'image.item.images',
                'image.item.user',
                'image.item.latestHistory',
            ])
            ->get()
            ->map(function ($vector) use ($queryVector) {

                $storedVector = is_array($vector->embedding)
                    ? $vector->embedding
                    : json_decode($vector->embedding, true);

                $score = $this->cosineSimilarity(
                    $queryVector,
                    $storedVector
                );

                return [
                    'score' => $score,
                    'item' => $vector->image->item,
                ];
            })
            ->sortByDesc('score')
            ->values();

        return $matches
            ->filter(fn ($match) => $match['score'] >= 0.70)
            ->pluck('item')
            ->unique('id')
            ->values();
    }

    protected function cosineSimilarity(
        array $a,
        array $b
    ): float {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        $length = min(count($a), count($b));

        for ($i = 0; $i < $length; $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA == 0 || $normB == 0) {
            return 0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}