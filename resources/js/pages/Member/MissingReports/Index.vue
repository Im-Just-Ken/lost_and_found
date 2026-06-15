<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

import { useDateFormat } from '@/composables/useDateFormat';
import { usePrimaryImage } from '@/composables/usePrimaryImage';

import { MapPin, CalendarDays, User, Camera } from 'lucide-vue-next';

import ImageSearchOverlay from '@/components/ImageSearchOverlay.vue';

const props = defineProps<{
    items: any[];
    imageSearch?: boolean;
    searchImage?: string | null;
}>();

const { formatDateTime } = useDateFormat();
const { getPrimaryImage } = usePrimaryImage();

const showImageSearch = ref(false);
const search = ref('');

const isImageSearch = computed(() => props.imageSearch === true);

const searchByImage = (file: File) => {
    const form = new FormData();
    form.append('image', file);

    router.post('/member/community/missing-reports/search-by-image', form, {
        forceFormData: true,
        preserveScroll: true,
        preserveState: false,
    });

    showImageSearch.value = false;
};

const filteredItems = computed(() => {
    if (isImageSearch.value || !search.value.trim()) {
        return props.items;
    }

    const keyword = search.value.toLowerCase();

    return props.items.filter(
        (item) =>
            item.title?.toLowerCase().includes(keyword) ||
            item.description?.toLowerCase().includes(keyword) ||
            item.location_text?.toLowerCase().includes(keyword) ||
            item.user?.name?.toLowerCase().includes(keyword),
    );
});

const clearImageSearch = () => {
    router.visit('/member/community/missing-reports');
};

const searchImageUrl = computed(() => {
    if (!props.searchImage) return null;
    return `data:image/*;base64,${props.searchImage}`;
});

const viewItem = (id: number) => {
    router.visit(`/member/community/missing-reports/${id}`);
};
</script>

<template>
    <div class="space-y-6 px-10 py-6">
        <!-- HEADER -->
        <div>
            <h1 class="text-2xl font-semibold">Missing Reports</h1>

            <p class="text-sm text-muted-foreground">
                View all reported lost and found items
            </p>
        </div>

        <!-- SEARCH BAR -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="w-72">
                    <Input
                        v-model="search"
                        :disabled="isImageSearch"
                        :placeholder="
                            isImageSearch
                                ? 'Viewing image search results'
                                : 'Search reported items...'
                        "
                    />
                </div>

                <Button
                    variant="outline"
                    size="icon"
                    @click="showImageSearch = true"
                >
                    <Camera class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <!-- IMAGE SEARCH OVERLAY -->
        <ImageSearchOverlay
            v-if="showImageSearch"
            @close="showImageSearch = false"
            @search="searchByImage"
        />

        <!-- IMAGE SEARCH INFO -->
        <div
            v-if="isImageSearch"
            class="flex items-center justify-between gap-4 rounded-xl border border-primary/20 bg-primary/5 px-4 py-3"
        >
            <!-- LEFT TEXT -->
            <!-- RIGHT IMAGE PREVIEW -->
            <div class="flex gap-3">
                <div v-if="searchImageUrl" class="gap-2">
                    <img
                        :src="searchImageUrl"
                        class="h-10 w-10 rounded-md border object-cover shadow-sm"
                    />
                </div>

                <div>
                    <p class="text-sm font-medium">
                        Showing visually similar items
                    </p>

                    <p class="text-xs text-muted-foreground">
                        Results are ranked using AI image similarity matching.
                    </p>
                </div>
            </div>

            <!-- CLEAR BUTTON -->
            <Button variant="ghost" size="sm" @click="clearImageSearch">
                Clear Search
            </Button>
        </div>

        <!-- CONTENT -->
        <div class="min-h-[400px]">
            <!-- GRID -->
            <div
                v-if="filteredItems.length > 0"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
            >
                <Card
                    v-for="item in filteredItems"
                    :key="item.id"
                    class="cursor-pointer transition hover:-translate-y-1 hover:shadow-md"
                    @click="viewItem(item.id)"
                >
                    <CardContent class="space-y-3 p-4">
                        <!-- HEADER -->
                        <div class="flex items-center justify-between">
                            <Badge variant="secondary">
                                {{ item.status.label }}
                            </Badge>

                            <span class="text-xs text-muted-foreground">
                                ID: #{{ item.id }}
                            </span>
                        </div>

                        <!-- IMAGE -->
                        <div class="relative">
                            <img
                                v-if="getPrimaryImage(item.images)"
                                :src="getPrimaryImage(item.images)?.path"
                                class="h-40 w-full rounded-md object-cover"
                            />

                            <!-- Optional similarity score -->
                            <Badge
                                v-if="isImageSearch && item.similarity"
                                class="absolute top-2 right-2"
                            >
                                {{ (item.similarity * 100).toFixed(1) }}% Match
                            </Badge>
                        </div>

                        <!-- TITLE -->
                        <h2 class="text-lg leading-tight font-semibold">
                            {{ item.title }}
                        </h2>

                        <!-- DESCRIPTION -->
                        <p class="line-clamp-2 text-sm text-muted-foreground">
                            {{ item.description }}
                        </p>

                        <!-- REPORTED BY -->
                        <div
                            class="flex items-center gap-1 text-xs text-muted-foreground"
                        >
                            <User class="h-4 w-4" />

                            <span>
                                {{ item.user?.name ?? 'Unknown User' }}
                            </span>
                        </div>

                        <!-- LOCATION -->
                        <div class="space-y-0.5 text-xs text-muted-foreground">
                            <div class="flex items-center gap-1">
                                <MapPin class="h-4 w-4" />

                                <span class="font-medium"> Loss Location </span>
                            </div>

                            <div class="pl-5">
                                {{
                                    item.location_text ?? 'No location provided'
                                }}
                            </div>
                        </div>

                        <!-- DATES -->
                        <div
                            class="space-y-2 border-t pt-3 text-xs text-muted-foreground"
                        >
                            <!-- CREATED -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <CalendarDays class="h-4 w-4" />

                                    <span> Reported </span>
                                </div>

                                <span>
                                    {{ formatDateTime(item.created_at) }}
                                </span>
                            </div>

                            <!-- LOST DATE -->
                            <div
                                v-if="item.date_lost"
                                class="flex items-center justify-between"
                            >
                                <div class="flex items-center gap-1">
                                    <CalendarDays
                                        class="h-4 w-4 text-red-400"
                                    />

                                    <span>Lost</span>
                                </div>

                                <span>
                                    {{ formatDateTime(item.date_lost) }}
                                </span>
                            </div>

                            <!-- RESOLVED -->
                            <div
                                v-if="item.resolved_at"
                                class="flex items-center justify-between"
                            >
                                <div class="flex items-center gap-1">
                                    <CalendarDays
                                        class="h-4 w-4 text-green-500"
                                    />

                                    <span> Resolved </span>
                                </div>

                                <span>
                                    {{ formatDateTime(item.resolved_at) }}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- EMPTY -->
            <div
                v-else
                class="flex min-h-[400px] flex-col items-center justify-center text-center text-muted-foreground"
            >
                <Camera class="mb-3 h-10 w-10 opacity-40" />

                <p class="text-lg font-medium">No reported items found</p>

                <p class="text-sm">
                    {{
                        isImageSearch
                            ? 'No visually similar items were found.'
                            : 'Try adjusting your search.'
                    }}
                </p>

                <Button
                    v-if="isImageSearch"
                    variant="outline"
                    class="mt-4"
                    @click="clearImageSearch"
                >
                    Back to All Reports
                </Button>
            </div>
        </div>
    </div>
</template>
