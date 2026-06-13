<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

import {
    PackageSearch,
    HandHelping,
    MapPin,
    CalendarDays,
    User,
    CheckCircle2,
} from 'lucide-vue-next';

import { useDateFormat } from '@/composables/useDateFormat';
import { usePrimaryImage } from '@/composables/usePrimaryImage';

const props = defineProps<{
    currentUserId: number;

    stats: {
        lost_items: number;
        found_items: number;
        resolved_items: number;
    };

    lostItems: any[];
    foundItems: any[];
    notifications: any[];
}>();

const { formatDateTime } = useDateFormat();
const { getPrimaryImage } = usePrimaryImage();

const lostSearch = ref('');
const foundSearch = ref('');

/* ---------------- LOST ITEMS ---------------- */
const filteredLostItems = computed(() => {
    if (!lostSearch.value) return props.lostItems;

    const keyword = lostSearch.value.toLowerCase();

    return props.lostItems.filter(
        (item: any) =>
            item.title?.toLowerCase().includes(keyword) ||
            item.description?.toLowerCase().includes(keyword) ||
            item.location_text?.toLowerCase().includes(keyword),
    );
});

/* ---------------- FOUND ITEMS ---------------- */
const filteredFoundItems = computed(() => {
    if (!foundSearch.value) return props.foundItems;

    const keyword = foundSearch.value.toLowerCase();

    return props.foundItems.filter(
        (item: any) =>
            item.title?.toLowerCase().includes(keyword) ||
            item.description?.toLowerCase().includes(keyword) ||
            item.location_text?.toLowerCase().includes(keyword),
    );
});

/* ---------------- NAVIGATION ---------------- */
const viewLostItem = (id: number) => {
    router.visit(`/member/items/${id}`);
};

const viewFoundItem = (id: number) => {
    router.visit(`/member/found-by-me/${id}`);
};

const getFoundStatusInfo = (item: any) => {
    switch (item.status.key) {
        case 'FOUND_PENDING':
            return {
                title: 'Verification Required',
                description:
                    'Please proceed to the HR office to complete the verification process',
                badge: 'Pending Review',
                class: 'border-blue-200 bg-blue-50',
            };

        case 'FOUND':
            return {
                title: 'Verified and Awaiting Claim',
                description:
                    'Your report has been successfully verified. The item is now waiting to be claimed by its rightful owner.',
                badge: 'Verified',
                class: 'border-green-200 bg-green-50',
            };

        case 'CLAIMED':
            return {
                title: 'Item Successfully Returned',
                description:
                    'This item has been claimed by its rightful owner. Thank you for helping reunite it with them.',
                badge: 'Completed',
                class: 'border-emerald-200 bg-emerald-50',
            };

        default:
            return {
                title: 'Report Submitted',
                description:
                    'Your found item report has been recorded and is currently being processed.',
                badge: 'In Progress',
                class: 'border-muted',
            };
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="space-y-6 p-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold tracking-tight">My Dashboard</h1>

            <p class="text-muted-foreground">
                Manage your lost item reports and items you've found.
            </p>
        </div>

        <!-- Statistics -->
        <div class="grid gap-4 md:grid-cols-3">
            <Card>
                <CardContent class="flex items-center gap-4 p-6">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <PackageSearch class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">
                            My Lost Items
                        </p>

                        <h2 class="text-3xl font-bold">
                            {{ stats.lost_items }}
                        </h2>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4 p-6">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <HandHelping class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">Found By Me</p>

                        <h2 class="text-3xl font-bold">
                            {{ stats.found_items }}
                        </h2>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4 p-6">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <CheckCircle2 class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <div>
                        <p class="text-sm text-muted-foreground">My Claims</p>

                        <h2 class="text-3xl font-bold">
                            {{ stats.resolved_items }}
                        </h2>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Content -->
        <div class="grid gap-6 xl:grid-cols-12">
            <!-- Lost Items -->
            <div class="space-y-6 xl:col-span-8">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <div>
                            <CardTitle>My Lost Items</CardTitle>
                            <CardDescription>
                                Recent lost item reports you've submitted.
                            </CardDescription>
                        </div>

                        <Badge variant="outline">
                            {{ filteredLostItems.length }}
                        </Badge>
                    </CardHeader>

                    <CardContent class="space-y-4">
                        <Input
                            v-model="lostSearch"
                            placeholder="Search lost items..."
                        />

                        <div
                            v-if="filteredLostItems.length"
                            class="overflow-hidden rounded-lg border"
                        >
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Item</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Location</TableHead>
                                        <TableHead>Date</TableHead>
                                        <TableHead class="text-right">
                                            Action
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>

                                <TableBody>
                                    <TableRow
                                        v-for="item in filteredLostItems.slice(
                                            0,
                                            5,
                                        )"
                                        :key="item.id"
                                    >
                                        <TableCell>
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <img
                                                    v-if="
                                                        getPrimaryImage(
                                                            item.images,
                                                        )
                                                    "
                                                    :src="
                                                        getPrimaryImage(
                                                            item.images,
                                                        )?.path
                                                    "
                                                    class="h-10 w-10 rounded-md object-cover"
                                                />

                                                <div>
                                                    <p class="font-medium">
                                                        {{ item.title }}
                                                    </p>

                                                    <p
                                                        class="text-xs text-muted-foreground"
                                                    >
                                                        #{{ item.id }}
                                                    </p>
                                                </div>
                                            </div>
                                        </TableCell>

                                        <TableCell>
                                            <Badge variant="secondary">
                                                {{ item.status.label }}
                                            </Badge>
                                        </TableCell>

                                        <TableCell>
                                            <div
                                                class="flex items-center gap-1 text-sm text-muted-foreground"
                                            >
                                                <MapPin class="h-4 w-4" />
                                                {{
                                                    item.location_text || 'N/A'
                                                }}
                                            </div>
                                        </TableCell>

                                        <TableCell class="text-sm">
                                            {{
                                                formatDateTime(item.created_at)
                                            }}
                                        </TableCell>

                                        <TableCell class="text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                @click="viewLostItem(item.id)"
                                            >
                                                View
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <div
                            v-else
                            class="flex min-h-[220px] flex-col items-center justify-center rounded-lg border border-dashed text-center"
                        >
                            <PackageSearch
                                class="mb-3 h-10 w-10 text-muted-foreground/40"
                            />

                            <p class="font-medium">No lost items yet</p>

                            <p class="text-sm text-muted-foreground">
                                Your reported lost items will appear here.
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Found By Me -->
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <div>
                            <CardTitle>Found By Me</CardTitle>
                            <CardDescription>
                                Items you've reported as found.
                            </CardDescription>
                        </div>

                        <Badge variant="outline">
                            {{ filteredFoundItems.length }}
                        </Badge>
                    </CardHeader>

                    <CardContent class="space-y-4">
                        <Input
                            v-model="foundSearch"
                            placeholder="Search found items..."
                        />

                        <div v-if="filteredFoundItems.length" class="space-y-3">
                            <div
                                v-for="item in filteredFoundItems.slice(0, 5)"
                                :key="item.id"
                                class="cursor-pointer rounded-xl border bg-background p-4 transition-all hover:border-primary/30 hover:shadow-sm"
                                @click="viewFoundItem(item.id)"
                            >
                                <div
                                    class="grid grid-cols-1 gap-4 lg:grid-cols-12"
                                >
                                    <!-- LEFT: Item Information -->
                                    <div class="space-y-3 lg:col-span-4">
                                        <div>
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <h3
                                                    class="text-sm font-semibold"
                                                >
                                                    {{ item.title }}
                                                </h3>

                                                <span
                                                    class="text-xs text-muted-foreground"
                                                >
                                                    #{{ item.id }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="space-y-2 text-xs text-muted-foreground"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <MapPin
                                                    class="h-3.5 w-3.5 shrink-0"
                                                />
                                                <span>
                                                    {{
                                                        item.location_text ||
                                                        'No location provided'
                                                    }}
                                                </span>
                                            </div>

                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <CalendarDays
                                                    class="h-3.5 w-3.5 shrink-0"
                                                />
                                                <span>
                                                    {{
                                                        formatDateTime(
                                                            item.created_at,
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RIGHT: Status Panel -->
                                    <div
                                        class="rounded-xl border p-4 lg:col-span-8"
                                        :class="getFoundStatusInfo(item).class"
                                    >
                                        <div
                                            class="flex items-start justify-between gap-3"
                                        >
                                            <div class="min-w-0 flex-1">
                                                <p
                                                    class="text-sm font-semibold"
                                                >
                                                    {{
                                                        getFoundStatusInfo(item)
                                                            .title
                                                    }}
                                                </p>

                                                <p
                                                    class="mt-2 text-xs leading-5 text-muted-foreground"
                                                >
                                                    {{
                                                        getFoundStatusInfo(item)
                                                            .description
                                                    }}
                                                </p>
                                            </div>

                                            <Badge
                                                variant="secondary"
                                                class="shrink-0 whitespace-nowrap"
                                            >
                                                {{
                                                    getFoundStatusInfo(item)
                                                        .badge
                                                }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-else
                            class="flex min-h-[220px] flex-col items-center justify-center rounded-lg border border-dashed text-center"
                        >
                            <HandHelping
                                class="mb-3 h-10 w-10 text-muted-foreground/40"
                            />

                            <p class="font-medium">No found items submitted</p>

                            <p class="text-sm text-muted-foreground">
                                Items you report as found will appear here.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <!-- Notifications -->
            <div class="xl:col-span-4">
                <Card class="h-full">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <div>
                            <CardTitle>Notifications</CardTitle>

                            <CardDescription>
                                Recent updates about your lost and found items.
                            </CardDescription>
                        </div>

                        <Badge variant="outline">
                            {{ notifications.length }}
                        </Badge>
                    </CardHeader>

                    <CardContent>
                        <div
                            v-if="notifications.length"
                            class="max-h-[700px] space-y-3 overflow-y-auto pr-1"
                        >
                            <div
                                v-for="notification in notifications"
                                :key="notification.id"
                                class="rounded-xl border bg-background p-4 transition-all hover:border-primary/30 hover:shadow-sm"
                            >
                                <div
                                    class="flex items-start justify-between gap-3"
                                >
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <Badge variant="secondary">
                                                {{
                                                    notification.action_type
                                                        .label
                                                }}
                                            </Badge>

                                            <span
                                                class="text-xs text-muted-foreground"
                                            >
                                                by
                                                <span class="font-medium">
                                                    {{
                                                        notification.user
                                                            ?.id ===
                                                        currentUserId
                                                            ? 'You'
                                                            : notification.user
                                                                  ?.name
                                                    }}
                                                </span>
                                            </span>
                                        </div>

                                        <p class="mt-2 text-sm font-medium">
                                            {{ notification.item?.title }}
                                        </p>

                                        <p
                                            v-if="notification.notes"
                                            class="mt-1 text-xs leading-relaxed text-muted-foreground"
                                        >
                                            {{ notification.notes }}
                                        </p>
                                    </div>

                                    <span
                                        class="shrink-0 text-xs text-muted-foreground"
                                    >
                                        {{
                                            formatDateTime(
                                                notification.created_at,
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div
                            v-else
                            class="flex min-h-[250px] flex-col items-center justify-center rounded-lg border border-dashed text-center"
                        >
                            <Bell
                                class="mb-3 h-10 w-10 text-muted-foreground/40"
                            />

                            <p class="font-medium">No notifications yet</p>

                            <p class="text-sm text-muted-foreground">
                                Updates about your reported and found items will
                                appear here.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
