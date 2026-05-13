<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { format } from 'date-fns';
import { toast } from 'vue-sonner';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

import { Dialog, DialogContent } from '@/components/ui/dialog';

import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from '@/components/ui/carousel';

import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

import { ShieldCheck } from 'lucide-vue-next';

import { CalendarDays, MapPin, Phone, User, Mail } from 'lucide-vue-next';
import { useItemProgress } from '@/composables/useItemProgress';

const props = defineProps<{
    item: any;
}>();

const { progressSteps, currentStep, progressWidth } = useItemProgress(
    props.item,
);

/**
 * PRIMARY IMAGE
 */
const primaryImage = computed(() => {
    if (!props.item.images?.length) return null;

    return (
        props.item.images.find((img: any) => img.is_primary) ||
        props.item.images[0]
    );
});

/**
 * IMAGE MODAL
 */
const isOpen = ref(false);
const activeIndex = ref(0);

const openGallery = (index: number) => {
    activeIndex.value = index;
    isOpen.value = true;
};

const markAsFound = () => {
    router.post(
        `/member/reported-items/${props.item.id}/found`,
        {},
        {
            preserveScroll: true,

            onSuccess: () => {
                toast.success('Item marked as found');
            },

            onError: () => {
                toast.error('Unable to mark item as found');
            },
        },
    );
};
const reviewMessage = computed(() => {
    if (props.item.status.value !== 1) return null;

    return {
        title: 'Verification Required',
        description:
            'Please proceed to the HR office to verify that the item currently in your possession matches this report. Thank you for taking the time to assist and help return the item to its rightful owner.',
    };
});
</script>

<template>
    <div class="max-w-5xl space-y-10 px-6 py-12 lg:mx-[300px]">
        <!-- HEADER -->
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold">Reported Item</h1>
                <p class="text-muted-foreground">View reported item details</p>
            </div>

            <Badge variant="secondary">
                {{ item.status.label }}
            </Badge>
        </div>

        <!-- ITEM INFORMATION -->
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
        <!-- REVIEW NOTICE -->
        <!-- REVIEW NOTICE -->
        <Alert
            v-if="reviewMessage"
            class="border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-900 dark:bg-amber-950/20 dark:text-amber-100"
        >
            <ShieldCheck class="h-5 w-5" />

            <AlertTitle class="mb-1">
                {{ reviewMessage.title }}
            </AlertTitle>

            <AlertDescription class="leading-relaxed">
                {{ reviewMessage.description }}
            </AlertDescription>
        </Alert>
        <Card>
            <CardHeader>
                <CardTitle>Item Information</CardTitle>
            </CardHeader>

            <CardContent class="space-y-6">
                <div v-if="primaryImage">
                    <img
                        :src="primaryImage.path"
                        class="h-[350px] w-full rounded-xl object-cover"
                    />
                </div>

                <div>
                    <h2 class="text-2xl font-semibold">
                        {{ item.title }}
                    </h2>
                </div>

                <div class="space-y-2">
                    <h3 class="text-sm font-medium text-muted-foreground">
                        Description
                    </h3>

                    <p class="leading-relaxed whitespace-pre-line">
                        {{ item.description || 'No description provided.' }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <!-- REPORTER -->
        <Card>
            <CardHeader>
                <CardTitle>Reporter Information</CardTitle>
            </CardHeader>

            <CardContent class="space-y-4">
                <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                    <div class="flex items-center gap-3">
                        <User class="h-4 w-4 text-muted-foreground" />
                        <div>
                            <p class="text-muted-foreground">Name</p>
                            <p class="font-medium">
                                {{ item.user?.name || 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <Mail class="h-4 w-4 text-muted-foreground" />
                        <div>
                            <p class="text-muted-foreground">Email</p>
                            <p class="font-medium">
                                {{ item.user?.email || 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <Phone class="h-4 w-4 text-muted-foreground" />
                        <div>
                            <p class="text-muted-foreground">Contact Number</p>
                            <p class="font-medium">
                                {{ item.contact_number || 'No contact number' }}
                            </p>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- LOSS DETAILS -->
        <Card>
            <CardHeader>
                <CardTitle>Loss Details</CardTitle>
            </CardHeader>

            <CardContent class="space-y-5">
                <div class="flex items-start gap-3">
                    <MapPin class="mt-0.5 h-5 w-5 text-muted-foreground" />
                    <div>
                        <p class="text-sm text-muted-foreground">Location</p>
                        <p class="font-medium">
                            {{ item.location_text || 'No location provided' }}
                        </p>
                    </div>
                </div>

                <div v-if="item.date_lost" class="flex items-start gap-3">
                    <CalendarDays
                        class="mt-0.5 h-5 w-5 text-muted-foreground"
                    />
                    <div>
                        <p class="text-sm text-muted-foreground">Date Lost</p>
                        <p class="font-medium">
                            {{ format(new Date(item.date_lost), 'PPP') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <CalendarDays
                        class="mt-0.5 h-5 w-5 text-muted-foreground"
                    />
                    <div>
                        <p class="text-sm text-muted-foreground">Reported At</p>
                        <p class="font-medium">
                            {{ format(new Date(item.created_at), 'PPP p') }}
                        </p>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- ALL IMAGES -->
        <Card v-if="item.images?.length">
            <CardHeader>
                <CardTitle>All Images</CardTitle>
            </CardHeader>

            <CardContent>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div
                        v-for="(img, index) in item.images"
                        :key="img.id"
                        class="relative"
                    >
                        <img
                            :src="img.path"
                            class="h-32 w-full cursor-pointer rounded-lg object-cover transition hover:opacity-80"
                            @click="openGallery(index)"
                        />

                        <Badge
                            v-if="img.is_primary"
                            class="absolute top-2 left-2"
                        >
                            Primary
                        </Badge>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- IMAGE MODAL -->
        <Dialog v-model:open="isOpen">
            <DialogContent
                class="max-w-5xl border-0 bg-transparent shadow-none"
            >
                <Carousel :opts="{ startIndex: activeIndex }" class="w-full">
                    <CarouselContent>
                        <CarouselItem v-for="img in item.images" :key="img.id">
                            <div class="flex justify-center">
                                <img
                                    :src="img.path"
                                    class="max-h-[80vh] rounded-xl object-contain"
                                />
                            </div>
                        </CarouselItem>
                    </CarouselContent>

                    <CarouselPrevious />
                    <CarouselNext />
                </Carousel>
            </DialogContent>
        </Dialog>

        <!-- ===== ACTION AREA (IMPROVED UX) ===== -->
        <Card
            v-if="item.status.value === 0"
            class="border-green-200 bg-green-50 dark:border-green-900 dark:bg-green-950/20"
        >
            <CardContent
                class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between"
            >
                <div class="space-y-1">
                    <h3
                        class="text-lg font-semibold text-green-700 dark:text-green-400"
                    >
                        Did you find this item?
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        Marking this helps notify the owner and speeds up
                        recovery.
                    </p>
                </div>

                <AlertDialog>
                    <AlertDialogTrigger as-child>
                        <Button size="lg" class="min-w-[180px]">
                            I Found This
                        </Button>
                    </AlertDialogTrigger>

                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>
                                Confirm Found Item
                            </AlertDialogTitle>

                            <AlertDialogDescription>
                                Are you sure you found this item? This will
                                notify the owner and mark it as pending
                                verification.
                            </AlertDialogDescription>
                        </AlertDialogHeader>

                        <AlertDialogFooter>
                            <AlertDialogCancel> Cancel </AlertDialogCancel>

                            <AlertDialogAction @click="markAsFound">
                                Yes, I Found It
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>
            </CardContent>
        </Card>

        <!-- BACK ACTION -->
        <div class="flex justify-end">
            <Button
                variant="ghost"
                @click="router.visit('/member/reported-items')"
            >
                Back
            </Button>
        </div>
    </div>
</template>
