import { ref } from 'vue';

export function useImageUpload(options?: {
    maxFiles?: number;
    maxSizeMB?: number;
}) {
    const MAX_FILES = options?.maxFiles ?? 6;
    const MAX_SIZE_MB = options?.maxSizeMB ?? 5;

    const files = ref<File[]>([]);
    const previews = ref<string[]>([]);

    const handleImages = (e: Event) => {
        const target = e.target as HTMLInputElement;
        if (!target.files) return;

        const newFiles = Array.from(target.files);

        newFiles.forEach((file) => {
            // ✅ type validation
            if (!file.type.startsWith('image/')) {
                console.warn('Invalid file type:', file.name);
                return;
            }

            // ✅ size validation
            if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                console.warn('File too large:', file.name);
                return;
            }

            // ✅ max limit
            if (files.value.length >= MAX_FILES) {
                console.warn('Max files reached');
                return;
            }

            // ✅ prevent duplicates
            const exists = files.value.some(
                (f) => f.name === file.name && f.size === file.size,
            );

            if (exists) return;

            files.value.push(file);
            previews.value.push(URL.createObjectURL(file));
        });

        // allow re-select same file
        target.value = '';
    };

    const removeImage = (index: number) => {
        files.value.splice(index, 1);
        previews.value.splice(index, 1);
    };

    const reset = () => {
        files.value = [];
        previews.value = [];
    };

    return {
        files,
        previews,
        handleImages,
        removeImage,
        reset,
    };
}
