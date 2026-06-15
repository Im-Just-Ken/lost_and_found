<script setup lang="ts">
import { ref } from 'vue';
import { UploadCloud, X, ImageIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'search', file: File): void;
}>();

const file = ref<File | null>(null);
const preview = ref<string | null>(null);

const handleSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (!target.files?.length) return;

    file.value = target.files[0];
    preview.value = URL.createObjectURL(file.value);
};

const handleDrop = (event: DragEvent) => {
    event.preventDefault();

    if (!event.dataTransfer?.files.length) return;

    file.value = event.dataTransfer.files[0];
    preview.value = URL.createObjectURL(file.value);
};

const submit = () => {
    if (!file.value) return;
    emit('search', file.value);
};
</script>

<template>
    <Teleport to="body">
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-6 backdrop-blur-sm"
        >
            <div
                class="relative w-full max-w-2xl rounded-2xl border bg-background p-8 shadow-2xl"
            >
                <!-- Close -->
                <button
                    class="absolute top-4 right-4 rounded-full p-2 hover:bg-muted"
                    @click="$emit('close')"
                >
                    <X class="h-5 w-5" />
                </button>

                <!-- Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-semibold">Search by Image</h2>

                    <p class="mt-2 text-sm text-muted-foreground">
                        Drag & drop an image or browse your device to find
                        visually similar lost items.
                    </p>
                </div>

                <!-- Dropzone -->
                <label
                    class="flex min-h-[280px] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-muted-foreground/20 transition hover:border-primary hover:bg-muted/30"
                    @dragover.prevent
                    @drop="handleDrop"
                >
                    <template v-if="!preview">
                        <UploadCloud
                            class="mb-4 h-14 w-14 text-muted-foreground"
                        />

                        <p class="font-medium">Drag & drop an image here</p>

                        <p class="mt-1 text-sm text-muted-foreground">
                            or click to browse
                        </p>
                    </template>

                    <template v-else>
                        <img
                            :src="preview"
                            class="max-h-56 rounded-lg object-contain shadow"
                        />

                        <p class="mt-4 text-sm text-muted-foreground">
                            {{ file?.name }}
                        </p>
                    </template>

                    <input
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="handleSelect"
                    />
                </label>

                <!-- Footer -->
                <div class="mt-6 flex justify-end gap-3">
                    <Button variant="outline" @click="$emit('close')">
                        Cancel
                    </Button>

                    <Button :disabled="!file" @click="submit">
                        <ImageIcon class="mr-2 h-4 w-4" />
                        Search Similar Images
                    </Button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
