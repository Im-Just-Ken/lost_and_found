export function usePrimaryImage() {
    const getPrimaryImage = (images: any[]) => {
        if (!images?.length) return null;

        return images.find((img) => img.is_primary) || images[0];
    };

    return {
        getPrimaryImage,
    };
}
