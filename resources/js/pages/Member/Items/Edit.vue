<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';
import { format } from 'date-fns';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { CalendarDate, parseDate } from '@internationalized/date';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ItemStatus } from '@/generated/enums';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

import { ImagePlus, X } from 'lucide-vue-next';
import { useItemProgress } from '@/composables/useItemProgress';

/**
 * PROPS
 */
const props = defineProps<{
    item: any;
}>();

const { progressSteps, currentStep, progressWidth } = useItemProgress(
    props.item,
);
/**
 * SAFE IMAGES
 */
const initialImages = Array.isArray(props.item.images) ? props.item.images : [];

/**
 * DATE
 */
const selectedDate = ref<CalendarDate | undefined>(
    props.item.date_lost
        ? parseDate(props.item.date_lost.split('T')[0])
        : undefined,
);

/**
 * IMAGE STATES
 */
const existingImages = ref([...initialImages]);
const newImages = ref<File[]>([]);
const newPreviews = ref<string[]>([]);
const removedImageIds = ref<number[]>([]);

/**
 * PRIMARY STATE
 */
const primaryType = ref<'existing' | 'new'>('existing');

const initialPrimaryIndex = initialImages.findIndex(
    (img: any) => img.is_primary,
);

const primaryIndex = ref(initialPrimaryIndex !== -1 ? initialPrimaryIndex : 0);

/**
 * FORM
 */
const form = useForm({
    title: props.item.title ?? '',
    description: props.item.description ?? '',
    contact_number: props.item.contact_number ?? '',
    location_text: props.item.location_text ?? '',
    type: 'lost',
    date_lost: props.item.date_lost ?? '',
    new_images: [] as File[],
    removed_images: [] as number[],
    primary_type: 'existing',
    primary_index: primaryIndex.value,
});

/**
 * HANDLE NEW IMAGES
 */
const handleImages = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files) return;

    const files = Array.from(target.files);

    files.forEach((file) => {
        if (!file.type.startsWith('image/')) return;

        newImages.value.push(file);
        newPreviews.value.push(URL.createObjectURL(file));
    });

    target.value = '';
};

/**
 * REMOVE EXISTING IMAGE
 */
const removeExistingImage = (index: number) => {
    const img = existingImages.value[index];

    if (!img) return;

    removedImageIds.value.push(img.id);
    existingImages.value.splice(index, 1);

    if (primaryType.value === 'existing') {
        if (index === primaryIndex.value) {
            primaryIndex.value = 0;
        } else if (index < primaryIndex.value) {
            primaryIndex.value--;
        }
    }
};

/**
 * REMOVE NEW IMAGE
 */
const removeNewImage = (index: number) => {
    newImages.value.splice(index, 1);
    newPreviews.value.splice(index, 1);

    if (primaryType.value === 'new') {
        if (index === primaryIndex.value) {
            primaryIndex.value = 0;
        } else if (index < primaryIndex.value) {
            primaryIndex.value--;
        }
    }
};

/**
 * SET PRIMARY
 */
const setPrimaryExisting = (index: number) => {
    primaryType.value = 'existing';
    primaryIndex.value = index;
};

const setPrimaryNew = (index: number) => {
    primaryType.value = 'new';
    primaryIndex.value = index;
};

/**
 * EDITABLE STATE
 */
const isEditable = computed(() => {
    return props.item.status.value === ItemStatus.LOST;
});

const submit = () => {
    form.new_images = newImages.value;
    form.type = 'lost';
    form.removed_images = removedImageIds.value;
    form.primary_type = primaryType.value;
    form.primary_index = primaryIndex.value;

    if (selectedDate.value) {
        const d = new Date(selectedDate.value);
        form.date_lost = !isNaN(d.getTime()) ? d.toISOString() : '';
    } else {
        form.date_lost = '';
    }

    form.put(`/member/items/${props.item.id}`, {
        forceFormData: true,
        preserveScroll: true,
        onStart: () => toast.loading('Updating item...'),

        onSuccess: () => {
            toast.dismiss();
            toast.success('Item updated successfully');
        },

        onError: (errors) => {
            toast.dismiss();
            const first = Object.values(errors)[0];
            toast.error((first as string) || 'Update failed');
        },
    });
};
</script>

<template>
    <div class="max-w-5xl space-y-10 px-6 py-12 lg:mx-[300px]">
        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-semibold">Edit Item</h1>
            <p class="text-muted-foreground">Update your reported item</p>
        </div>

        <form @submit.prevent="submit" class="space-y-10">
            <!-- ITEM INFO -->
            <!-- ITEM PROGRESS -->
            <Card>
                <CardContent class="space-y-6 p-6">
                    <div class="relative">
                        <!-- BACKGROUND LINE -->
                        <div
                            class="absolute top-4 left-0 h-1 w-full rounded-full bg-muted"
                        />

                        <!-- ACTIVE LINE -->
                        <div
                            class="absolute top-4 left-0 h-1 rounded-full bg-primary transition-all duration-700 ease-in-out"
                            :style="{ width: progressWidth }"
                        />

                        <!-- STEPS -->
                        <div class="relative flex justify-between">
                            <div
                                v-for="(step, index) in progressSteps"
                                :key="step.label"
                                class="flex flex-col items-center gap-2"
                            >
                                <!-- CIRCLE -->
                                <div
                                    class="z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 text-xs font-semibold transition-all duration-500"
                                    :class="
                                        index < currentStep
                                            ? 'scale-100 border-primary bg-primary text-primary-foreground'
                                            : index === currentStep
                                              ? 'scale-110 animate-pulse border-primary bg-primary text-primary-foreground shadow-lg ring-4 ring-primary/20'
                                              : 'scale-100 border-muted bg-background text-muted-foreground'
                                    "
                                >
                                    {{ index + 1 }}
                                </div>

                                <!-- LABEL -->
                                <div class="text-center">
                                    <p
                                        class="text-xs font-medium transition-colors duration-300"
                                        :class="
                                            index <= currentStep
                                                ? 'text-foreground'
                                                : 'text-muted-foreground'
                                        "
                                    >
                                        {{ step.label }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Item Information</CardTitle>
                </CardHeader>

                <CardContent class="space-y-5">
                    <Input v-model="form.title" placeholder="Item title" />
                    <Textarea
                        v-model="form.description"
                        placeholder="Describe your item..."
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
                    <Input
                        v-model="form.location_text"
                        placeholder="Location"
                    />

                    <Popover>
                        <PopoverTrigger as-child>
                            <Button variant="outline" class="w-full">
                                {{
                                    selectedDate
                                        ? format(selectedDate, 'PPP')
                                        : 'Select lost date'
                                }}
                            </Button>
                        </PopoverTrigger>

                        <PopoverContent>
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
                    <!-- UPLOAD -->
                    <label
                        class="flex cursor-pointer flex-col items-center justify-center border border-dashed p-6"
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

                    <!-- EXISTING -->
                    <div v-if="existingImages.length">
                        <h3 class="text-sm font-medium">Existing Images</h3>

                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div
                                v-for="(img, index) in existingImages"
                                :key="img.id"
                                class="relative"
                            >
                                <img
                                    :src="img.path"
                                    class="h-28 w-full rounded object-cover"
                                />

                                <Button
                                    size="sm"
                                    type="button"
                                    @click="setPrimaryExisting(index)"
                                    class="absolute bottom-1 left-1 text-xs"
                                >
                                    {{
                                        primaryType === 'existing' &&
                                        primaryIndex === index
                                            ? 'Primary'
                                            : 'Set'
                                    }}
                                </Button>

                                <Button
                                    size="icon"
                                    type="button"
                                    variant="destructive"
                                    @click="removeExistingImage(index)"
                                    class="absolute top-1 right-1 h-6 w-6"
                                >
                                    <X class="h-3 w-3" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <div v-if="newPreviews.length">
                        <h3 class="text-sm font-medium">New Images</h3>

                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div
                                v-for="(img, index) in newPreviews"
                                :key="index"
                                class="relative"
                            >
                                <img
                                    :src="img"
                                    class="h-28 w-full rounded object-cover"
                                />

                                <Button
                                    size="sm"
                                    type="button"
                                    @click="setPrimaryNew(index)"
                                    class="absolute bottom-1 left-1 text-xs"
                                >
                                    {{
                                        primaryType === 'new' &&
                                        primaryIndex === index
                                            ? 'Primary'
                                            : 'Set'
                                    }}
                                </Button>

                                <Button
                                    size="icon"
                                    type="button"
                                    variant="destructive"
                                    @click="removeNewImage(index)"
                                    class="absolute top-1 right-1 h-6 w-6"
                                >
                                    <X class="h-3 w-3" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card
                v-if="!isEditable"
                class="border-amber-200 bg-amber-50 dark:border-amber-900 dark:bg-amber-950/20"
            >
                <CardContent class="p-5">
                    <div class="space-y-1">
                        <h3
                            class="font-semibold text-amber-700 dark:text-amber-400"
                        >
                            Editing Disabled
                        </h3>

                        <p class="text-sm text-muted-foreground">
                            This item can no longer be edited because it is
                            already in progress or resolved.
                        </p>
                    </div>
                </CardContent>
            </Card>
            <!-- SUBMIT -->
            <div class="flex justify-end gap-2">
                <Button
                    type="button"
                    variant="outline"
                    @click="router.visit('/member/items')"
                >
                    Back
                </Button>

                <Button v-if="isEditable" type="submit"> Update Item </Button>
            </div>
        </form>
    </div>
</template>
