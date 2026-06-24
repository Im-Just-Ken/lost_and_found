<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { format } from 'date-fns';
import { toast } from 'vue-sonner';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useDateFormat } from '@/composables/useDateFormat';

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

import { CalendarDays, MapPin, Phone, User, Mail } from 'lucide-vue-next';

const props = defineProps<{
    item: any;
}>();

const date = useDateFormat();

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

const openGallery = (index: any) => {
    activeIndex.value = index;
    isOpen.value = true;
};

const visibleHistories = computed(() => {
    return (props.item.histories || []).filter(
        (history: any) => ![0, 1].includes(history.action_type.value),
    );
});

const markAsFound = () => {
    router.post(
        `/member/community/missing-reports/${props.item.id}/found`,
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

const deleteItem = () => {
    router.delete(`/admin/reported-items/missing/${props.item.id}`, {
        preserveScroll: true,

        onSuccess: () => {
            toast.success('Missing report deleted successfully');
        },

        onError: () => {
            toast.error('Unable to delete missing report');
        },
    });
};
</script>

<template>
    <div class="mx-auto max-w-7xl space-y-6 px-6 py-12">
        <!-- HEADER -->
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold">Missing Report</h1>
                <!-- <p class="text-muted-foreground">View reported item details</p> -->
            </div>

            <Badge variant="secondary">
                {{ item.status.label }}
            </Badge>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <!-- LEFT COLUMN -->
            <div class="space-y-6">
                <!-- ITEM INFORMATION -->
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
                            <h3
                                class="text-sm font-medium text-muted-foreground"
                            >
                                Description
                            </h3>

                            <p class="leading-relaxed whitespace-pre-line">
                                {{
                                    item.description ||
                                    'No description provided.'
                                }}
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
                        <div
                            class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2"
                        >
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
                                    <p class="text-muted-foreground">
                                        Contact Number
                                    </p>
                                    <p class="font-medium">
                                        {{
                                            item.contact_number ||
                                            'No contact number'
                                        }}
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
                            <MapPin
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Location
                                </p>
                                <p class="font-medium">
                                    {{
                                        item.location_text ||
                                        'No location provided'
                                    }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="item.date_lost"
                            class="flex items-start gap-3"
                        >
                            <CalendarDays
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Date Lost
                                </p>
                                <p class="font-medium">
                                    {{
                                        format(new Date(item.date_lost), 'PPP')
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <CalendarDays
                                class="mt-0.5 h-5 w-5 text-muted-foreground"
                            />
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Reported At
                                </p>
                                <p class="font-medium">
                                    {{
                                        format(
                                            new Date(item.created_at),
                                            'PPP p',
                                        )
                                    }}
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
            </div>
            <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
                <Card>
                    <CardHeader>
                        <CardTitle>Activity History</CardTitle>
                    </CardHeader>

                    <CardContent>
                        <!-- Has history -->
                        <div
                            v-if="visibleHistories.length"
                            class="max-h-[500px] space-y-1 overflow-y-auto pr-2"
                        >
                            <div
                                v-for="history in visibleHistories"
                                :key="history.id"
                                class="border-l py-3 pl-4"
                            >
                                <div class="flex items-center justify-between">
                                    <Badge variant="outline">
                                        {{ history.action_type.label }}
                                    </Badge>

                                    <span class="text-sm text-muted-foreground">
                                        {{
                                            date.formatDateTime(
                                                history.created_at,
                                            )
                                        }}
                                    </span>
                                </div>

                                <p v-if="history.notes" class="mt-2 text-sm">
                                    {{ history.notes }}
                                </p>

                                <p
                                    v-if="history.user"
                                    class="mt-1 text-xs text-muted-foreground"
                                >
                                    {{ history.user.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div
                            v-else
                            class="flex min-h-[120px] items-center justify-center rounded-lg border border-dashed text-center"
                        >
                            <div>
                                <p class="font-medium">No activity yet</p>
                                <p class="text-sm text-muted-foreground">
                                    Activity records will appear here once
                                    actions are performed.
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
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

        <!-- BACK ACTION -->
        <div class="flex justify-end gap-2">
            <AlertDialog>
                <AlertDialogTrigger as-child>
                    <Button variant="destructive"> Delete Report </Button>
                </AlertDialogTrigger>

                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>
                            Delete Missing Report?
                        </AlertDialogTitle>

                        <AlertDialogDescription>
                            This action cannot be undone. The report and its
                            related images/history may be permanently removed.
                        </AlertDialogDescription>
                    </AlertDialogHeader>

                    <AlertDialogFooter>
                        <AlertDialogCancel> Cancel </AlertDialogCancel>

                        <AlertDialogAction @click="deleteItem">
                            Delete
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            <Button
                variant="ghost"
                @click="router.visit('/admin/reported-items/missing')"
            >
                Back
            </Button>
        </div>
    </div>
</template>
