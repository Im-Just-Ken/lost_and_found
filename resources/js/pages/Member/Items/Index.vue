<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { useDateFormat } from '@/composables/useDateFormat';
import { MapPin, CalendarDays } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { usePrimaryImage } from '@/composables/usePrimaryImage';

const props = defineProps<{
    items: any[];
}>();

const { formatDateTime } = useDateFormat();
const search = ref('');

const filteredItems = computed(() => {
    if (!search.value) return props.items;

    return props.items.filter(
        (item) =>
            item.title.toLowerCase().includes(search.value.toLowerCase()) ||
            item.description
                ?.toLowerCase()
                .includes(search.value.toLowerCase()) ||
            item.location_text
                ?.toLowerCase()
                .includes(search.value.toLowerCase()),
    );
});

const { getPrimaryImage } = usePrimaryImage();

const viewItem = (id: number) => {
    router.visit(`/member/items/${id}/edit`);
};
</script>

<template>
    <div class="space-y-6 px-10 py-6">
        <!-- HEADER -->
        <div>
            <h1 class="text-2xl font-semibold">My Items</h1>
            <p class="text-sm text-muted-foreground">
                Manage your reported items
            </p>
        </div>

        <!-- SEARCH -->
        <!-- SEARCH + ACTION -->
        <div class="flex items-center justify-between gap-4">
            <!-- SEARCH -->
            <div class="w-72">
                <Input
                    v-model="search"
                    type="text"
                    placeholder="Search items..."
                />
            </div>

            <!-- BUTTON -->
            <Button @click="router.visit('/member/items/create')">
                Report Lost Item
            </Button>
        </div>

        <!-- GRID WRAPPER (IMPORTANT: always exists) -->
        <div class="min-h-[400px]">
            <!-- ITEMS -->
            <div
                v-if="filteredItems.length > 0"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4"
            >
                <Card
                    v-for="item in filteredItems"
                    :key="item.id"
                    class="cursor-pointer transition hover:shadow-md"
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
                        <img
                            v-if="getPrimaryImage(item.images)"
                            :src="getPrimaryImage(item.images)?.path"
                            class="h-40 w-full rounded-md object-cover"
                        />

                        <!-- TITLE -->
                        <h2 class="text-lg leading-tight font-semibold">
                            {{ item.title }}
                        </h2>

                        <!-- DESCRIPTION -->
                        <p class="line-clamp-2 text-sm text-muted-foreground">
                            {{ item.description }}
                        </p>

                        <!-- LOSS LOCATION -->
                        <div class="space-y-0.5 text-xs text-muted-foreground">
                            <div class="flex items-center gap-1">
                                <MapPin class="h-4 w-4" />
                                <span class="font-medium">Loss Location</span>
                            </div>

                            <div class="pl-5">
                                {{
                                    item.location_text ?? 'No location provided'
                                }}
                            </div>
                        </div>

                        <!-- DATES SECTION -->
                        <div
                            class="space-y-2 border-t pt-3 text-xs text-muted-foreground"
                        >
                            <!-- CREATED -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <CalendarDays class="h-4 w-4" />
                                    <span>Reported</span>
                                </div>
                                <span>{{
                                    formatDateTime(item.created_at)
                                }}</span>
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
                                <span>{{
                                    formatDateTime(item.date_lost)
                                }}</span>
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
                                    <span>Resolved</span>
                                </div>
                                <span>{{
                                    formatDateTime(item.resolved_at)
                                }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- EMPTY STATE (DO NOT REMOVE SPACE) -->
            <div
                v-else
                class="flex min-h-[400px] flex-col items-center justify-center text-center text-muted-foreground"
            >
                <p class="text-lg font-medium">No items found</p>
                <p class="text-sm">Try adjusting your search</p>
            </div>
        </div>
    </div>
</template>
