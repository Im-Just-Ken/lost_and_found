<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { format } from 'date-fns';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

import { MapPin, ImagePlus, X } from 'lucide-vue-next';
import { useImageUpload } from '@/composables/useImageUpload';
/**
 * FORM
 */
const form = useForm({
    title: '',
    type: 'lost',
    description: '',
    contact_number: '',
    location_text: '',
    date_lost: '',
    images: [] as File[],
    primary_index: 0,
});

const {
    files: images,
    previews,
    handleImages,
    removeImage,
} = useImageUpload({
    maxFiles: 6,
    maxSizeMB: 5,
});

/**
 * DATE (UI state)
 */
const selectedDate = ref<Date | undefined>(undefined);
/**
 * IMAGES
 */

/**
 * COMPUTED FINAL DATE (SYNC TO FORM)
 */
const formattedDate = computed(() => {
    if (!selectedDate.value) return '';
    return selectedDate.value.toISOString();
});

const primaryIndex = ref(0);

/**
 * SUBMIT
 */
// const submit = () => {
//     console.log('SUBMIT FIRED');

//     form.post('/member/items', {
//         forceFormData: true,
//         onStart: () => console.log('START'),
//         onSuccess: () => console.log('SUCCESS'),
//         onError: (err) => console.log('ERROR', err),
//     });
// };

const submit = () => {
    form.primary_index = primaryIndex.value;
    form.images = images.value;

    if (selectedDate.value) {
        const date = new Date(selectedDate.value);
        form.date_lost = !isNaN(date.getTime()) ? date.toISOString() : '';
    } else {
        form.date_lost = '';
    }

    form.post('/member/items', {
        forceFormData: true,

        onStart: () => {
            toast.loading('Submitting report...');
        },

        onSuccess: () => {
            toast.dismiss(); // remove loading
            toast.success('Item reported successfully');
        },

        onError: (errors) => {
            toast.dismiss();

            // 🔥 show first validation error (clean UX)
            const firstError = Object.values(errors)[0];

            if (firstError) {
                toast.error(firstError as string);
            } else {
                toast.error('Something went wrong.');
            }
        },
    });
};
watch(images, (val) => {
    form.images = val;
});

watch(primaryIndex, (val) => {
    form.primary_index = val;
});
</script>
<template>
    <div class="max-w-5xl space-y-10 px-6 py-12 lg:mx-[300px]">
        <!-- HEADER -->
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold tracking-tight">
                Report Lost Item
            </h1>
            <p class="text-muted-foreground">
                Provide details to help others return your item
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-10">
            <!-- ITEM INFO -->
            <Card>
                <CardHeader>
                    <CardTitle>Item Information</CardTitle>
                </CardHeader>

                <CardContent class="space-y-5">
                    <Input v-model="form.title" placeholder="Item title" />
                    <Textarea
                        v-model="form.description"
                        placeholder="Describe your item..."
                        class="min-h-[120px]"
                    />
                    <Input
                        v-model="form.contact_number"
                        placeholder="Contact number"
                    />
                </CardContent>
            </Card>

            <!-- LOSS DETAILS -->
            <Card>
                <CardHeader>
                    <CardTitle>Loss Details</CardTitle>
                </CardHeader>

                <CardContent class="space-y-5">
                    <div
                        class="flex items-center gap-2 text-sm text-muted-foreground"
                    >
                        <MapPin class="h-4 w-4" />
                        Location where item was lost
                    </div>

                    <Input
                        v-model="form.location_text"
                        placeholder="e.g. Greenbelt Makati"
                    />

                    <!-- DATE PICKER -->
                    <Popover>
                        <PopoverTrigger as-child>
                            <Button
                                variant="outline"
                                class="w-full justify-start font-normal"
                            >
                                {{
                                    selectedDate
                                        ? format(selectedDate, 'PPP')
                                        : 'Select lost date'
                                }}
                            </Button>
                        </PopoverTrigger>

                        <PopoverContent class="w-auto p-0">
                            <Calendar v-model="selectedDate" />
                        </PopoverContent>
                    </Popover>
                </CardContent>
            </Card>

            <!-- IMAGES -->
            <Card>
                <CardHeader>
                    <CardTitle>Images</CardTitle>
                </CardHeader>

                <CardContent class="space-y-6">
                    <!-- DROPZONE -->
                    <label
                        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border border-dashed p-10 text-sm text-muted-foreground transition hover:bg-muted/40"
                    >
                        <ImagePlus class="h-6 w-6" />
                        Upload images

                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            class="hidden"
                            @change="handleImages"
                        />
                    </label>

                    <!-- PREVIEW -->
                    <div
                        v-if="previews.length"
                        class="grid grid-cols-2 gap-4 md:grid-cols-4"
                    >
                        <div
                            v-for="(img, index) in previews"
                            :key="index"
                            class="group relative"
                        >
                            <img
                                :src="img"
                                class="h-28 w-full rounded-md object-cover"
                            />

                            <!-- PRIMARY BUTTON -->
                            <Button
                                type="button"
                                size="sm"
                                variant="secondary"
                                @click="primaryIndex = index"
                                class="absolute bottom-2 left-2 text-xs"
                                :class="
                                    primaryIndex === index
                                        ? 'bg-primary text-white hover:bg-primary'
                                        : ''
                                "
                            >
                                {{
                                    primaryIndex === index
                                        ? 'Primary'
                                        : 'Set Primary'
                                }}
                            </Button>

                            <!-- REMOVE BUTTON -->
                            <Button
                                type="button"
                                size="icon"
                                variant="destructive"
                                @click="removeImage(index)"
                                class="absolute top-2 right-2 h-7 w-7 opacity-0 transition group-hover:opacity-100"
                            >
                                <X class="h-3 w-3" />
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- SUBMIT -->
            <div class="flex justify-end">
                <Button type="submit" class="px-6"> Submit Report </Button>
            </div>
        </form>
    </div>
</template>
