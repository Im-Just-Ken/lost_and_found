<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

import { Input } from '@/components/ui/input';
import { usePagination } from '@/composables/usePagination';

import ItemStatusChart from '@/components/charts/ItemStatusChart.vue';
import { useDateFormat } from '@/composables/useDateFormat';
import {
    Users,
    PackageSearch,
    ClipboardCheck,
    CheckCircle2,
    HandHelping,
    ClipboardCheckIcon,
} from 'lucide-vue-next';

import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectItem,
} from '@/components/ui/select';

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { MapPin, User } from 'lucide-vue-next';
import { usePrimaryImage } from '@/composables/usePrimaryImage';

const props = defineProps<{
    stats: {
        members: number;
        missing: {
            total: number;
            week: number;
        };
        pending_review: {
            total: number;
            week: number;
        };
        found: {
            total: number;
            week: number;
        };
        claimed: {
            total: number;
            week: number;
        };
    };

    statusChart: {
        status: string;
        count: number;
    }[];
    lostItems: any[];
    pendingItems: any[];
    recentActivity: any[];
    authUser: {
        id: number;
        name: string;
        roles: {
            id: number;
            name: string;
            label?: string;
        }[];
    };
}>();

const date = useDateFormat();

const ready = ref(false);

/* ---------------- SEARCH ---------------- */
const search = ref('');

const filteredItems = computed(() => {
    let data = props.lostItems;

    if (search.value) {
        const keyword = search.value.toLowerCase();

        data = data.filter(
            (item: any) =>
                item.title?.toLowerCase().includes(keyword) ||
                item.user?.name?.toLowerCase().includes(keyword) ||
                item.location_text?.toLowerCase().includes(keyword),
        );
    }

    return data;
});

/* ---------------- PAGINATION ---------------- */
const {
    currentPage,
    perPage,
    paginatedData,
    totalPages,
    total,
    from,
    to,
    next,
    prev,
} = usePagination(() => filteredItems.value);

const { formatDateTime } = useDateFormat();
const { getPrimaryImage } = usePrimaryImage();

onMounted(() => {
    requestAnimationFrame(() => {
        setTimeout(() => {
            ready.value = true;
        }, 80);
    });
});

const viewItem = (id: number) => {
    router.visit(`/admin/reported-items/missing/${id}`);
};

const viewPendingItem = (id: number) => {
    router.visit(`/admin/reported-items/pending-verification/${id}`);
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="space-y-6 p-6">
        <!-- HEADER -->
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p class="text-muted-foreground">
                Overview of system activity, item statistics, and recent reports
            </p>
        </div>

        <!-- KPI -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <Card>
                <CardContent class="flex items-center gap-4">
                    <!-- Icon -->
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <Users class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-muted-foreground">
                            Members
                        </p>

                        <h2 class="text-3xl leading-none font-bold">
                            {{ stats.members }}
                        </h2>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4">
                    <!-- Icon -->
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <PackageSearch class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-muted-foreground">
                            Missing Items
                        </p>

                        <h2 class="text-3xl leading-none font-bold">
                            {{ stats.missing.total }}
                        </h2>

                        <p class="mt-1 text-xs text-muted-foreground">
                            +{{ stats.missing.week }} this week
                        </p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4">
                    <!-- Icon -->
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <ClipboardCheckIcon
                            class="h-7 w-7 text-muted-foreground"
                        />
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-muted-foreground">
                            Pending Review Items
                        </p>

                        <h2 class="text-3xl leading-none font-bold">
                            {{ stats.pending_review.total }}
                        </h2>

                        <p class="mt-1 text-xs text-muted-foreground">
                            +{{ stats.pending_review.week }} this week
                        </p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4">
                    <!-- Icon -->
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <CheckCircle2 class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-muted-foreground">
                            Found Items
                        </p>

                        <h2 class="text-3xl leading-none font-bold">
                            {{ stats.found.total }}
                        </h2>

                        <p class="mt-1 text-xs text-muted-foreground">
                            +{{ stats.found.week }} this week
                        </p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardContent class="flex items-center gap-4">
                    <!-- Icon -->
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
                    >
                        <HandHelping class="h-7 w-7 text-muted-foreground" />
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col">
                        <p class="text-sm font-medium text-muted-foreground">
                            Claimed Items
                        </p>

                        <h2 class="text-3xl leading-none font-bold">
                            {{ stats.claimed.total }}
                        </h2>

                        <p class="mt-1 text-xs text-muted-foreground">
                            +{{ stats.claimed.week }} this week
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- CHART + RECENT -->
        <div class="grid gap-4 lg:grid-cols-2">
            <!-- STATUS CHART -->
            <Card>
                <CardHeader>
                    <CardTitle>Item Status Distribution</CardTitle>
                    <CardDescription>
                        Overview of all item statuses.
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <div v-if="ready" class="h-[320px]">
                        <ItemStatusChart
                            :missing="stats.missing.total"
                            :pending-review="stats.pending_review.total"
                            :found="stats.found.total"
                            :claimed="stats.claimed.total"
                        />
                    </div>

                    <div
                        v-else
                        class="flex h-[320px] items-center justify-center text-muted-foreground"
                    >
                        Loading chart...
                    </div>
                </CardContent>
            </Card>

            <!-- RECENT ACTIVITY -->
            <Card class="h-full">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0"
                >
                    <div>
                        <CardTitle>Activity History</CardTitle>
                        <CardDescription>
                            Latest system actions and updates
                        </CardDescription>
                    </div>
                </CardHeader>

                <CardContent>
                    <!-- Activity List -->
                    <div
                        v-if="recentActivity.length"
                        class="max-h-[350px] space-y-5 overflow-y-auto pr-2"
                    >
                        <div
                            v-for="history in recentActivity"
                            :key="history.id"
                            class="relative rounded-lg border bg-muted/30 p-3 transition hover:bg-muted/50"
                        >
                            <!-- Timeline dot -->

                            <!-- Top row -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <p
                                        v-if="history.item"
                                        class="text-sm leading-snug font-medium"
                                    >
                                        #{{ history.item.id }} •
                                        {{ history.item.title }}
                                    </p>
                                </div>

                                <span
                                    class="text-xs whitespace-nowrap text-muted-foreground"
                                >
                                    {{
                                        date.formatDateTime(history.created_at)
                                    }}
                                </span>
                            </div>

                            <!-- Notes -->
                            <p
                                v-if="history.notes"
                                class="mt-2 text-sm text-muted-foreground"
                            >
                                {{ history.notes }}
                            </p>

                            <!-- Footer -->
                            <div
                                class="mt-3 flex flex-wrap items-center gap-1.5 text-xs"
                            >
                                <span class="text-muted-foreground">by</span>

                                <span class="font-medium text-foreground">
                                    {{ history.user.name }}
                                    <span
                                        v-if="history.user.id === authUser.id"
                                        class="text-muted-foreground"
                                    >
                                        (You)
                                    </span>
                                </span>
                                •
                                <span class="font-medium text-foreground">
                                    <Badge
                                        v-for="role in history.user.roles"
                                        :key="role.id"
                                        variant="secondary"
                                        class="h-5 text-xs font-medium"
                                    >
                                        {{ role.label ?? role.name }}
                                    </Badge>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Empty state -->
                    <div
                        v-else
                        class="flex min-h-[160px] flex-col items-center justify-center rounded-lg border border-dashed text-center"
                    >
                        <p class="text-sm font-medium">No activity yet</p>
                        <p class="text-xs text-muted-foreground">
                            System actions will appear here once users interact
                            with items.
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
        <!-- RECENT ITEMS TABLE -->
        <!-- CONTENT -->
        <div class="grid gap-4 xl:grid-cols-3">
            <div class="xl:col-span-2">
                <Card class="h-full">
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0"
                    >
                        <div>
                            <CardTitle>Recent Reported Items</CardTitle>
                            <CardDescription>
                                Latest lost and found reports submitted to the
                                system.
                            </CardDescription>
                        </div>

                        <div class="w-72">
                            <Input
                                v-model="search"
                                placeholder="Search items, user, or location..."
                            />
                        </div>
                    </CardHeader>

                    <CardContent>
                        <div
                            v-if="filteredItems.length > 0"
                            class="overflow-hidden rounded-lg border"
                        >
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="w-[70px]"
                                            >ID</TableHead
                                        >
                                        <TableHead>Item Name</TableHead>

                                        <TableHead>Status</TableHead>
                                        <TableHead>Location</TableHead>
                                        <TableHead>Date Reported</TableHead>
                                        <TableHead>Reported By</TableHead>
                                        <TableHead class="text-right">
                                            Action
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>

                                <TableBody>
                                    <TableRow
                                        v-for="item in paginatedData"
                                        :key="item.id"
                                        class="cursor-pointer hover:bg-muted/50"
                                        @click="viewItem(item.id)"
                                    >
                                        <!-- ID -->
                                        <TableCell class="font-medium">
                                            #{{ item.id }}
                                        </TableCell>

                                        <!-- ITEM -->
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
                                                </div>
                                            </div>
                                        </TableCell>

                                        <TableCell>
                                            <Badge variant="secondary">
                                                {{ item.status?.label }}
                                            </Badge>
                                        </TableCell>

                                        <!-- LOCATION -->
                                        <TableCell
                                            class="text-sm text-muted-foreground"
                                        >
                                            <div
                                                class="flex items-center gap-1"
                                            >
                                                <MapPin class="h-4 w-4" />
                                                {{
                                                    item.location_text ?? 'N/A'
                                                }}
                                            </div>
                                        </TableCell>

                                        <!-- DATE -->
                                        <TableCell
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{
                                                formatDateTime(item.created_at)
                                            }}
                                        </TableCell>

                                        <!-- USER -->
                                        <TableCell>
                                            <div
                                                class="flex items-center gap-1 text-sm text-muted-foreground"
                                            >
                                                <User class="h-4 w-4" />
                                                {{
                                                    item.user?.name ?? 'Unknown'
                                                }}
                                            </div>
                                        </TableCell>

                                        <!-- ACTION -->
                                        <TableCell class="text-right">
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                @click.stop="viewItem(item.id)"
                                            >
                                                View
                                            </Button>
                                        </TableCell>
                                    </TableRow>

                                    <TableRow v-if="filteredItems.length === 0">
                                        <TableCell
                                            colspan="7"
                                            class="py-6 text-center text-muted-foreground"
                                        >
                                            No reported items found.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>

                        <!-- EMPTY -->
                        <div
                            v-else
                            class="flex min-h-[220px] items-center justify-center text-muted-foreground"
                        >
                            No reported items found.
                        </div>

                        <!-- PAGINATION -->
                        <div
                            v-if="filteredItems.length"
                            class="mt-4 flex items-center justify-between pt-2"
                        >
                            <div class="text-sm text-muted-foreground">
                                Showing
                                <span class="font-medium text-foreground">{{
                                    from
                                }}</span>
                                to
                                <span class="font-medium text-foreground">{{
                                    to
                                }}</span>
                                of
                                <span class="font-medium text-foreground">{{
                                    total
                                }}</span>
                                reported items
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Per Page -->
                                <Select v-model="perPage">
                                    <SelectTrigger class="w-[90px]">
                                        <SelectValue placeholder="10" />
                                    </SelectTrigger>

                                    <SelectContent>
                                        <SelectItem :value="5">5</SelectItem>
                                        <SelectItem :value="10">10</SelectItem>
                                        <SelectItem :value="15">15</SelectItem>
                                        <SelectItem :value="20">20</SelectItem>
                                        <SelectItem :value="50">50</SelectItem>
                                    </SelectContent>
                                </Select>

                                <!-- Prev -->
                                <Button
                                    variant="outline"
                                    size="sm"
                                    :disabled="currentPage === 1"
                                    @click="prev"
                                >
                                    Prev
                                </Button>

                                <!-- Page Counter -->
                                <span class="min-w-[60px] text-center text-sm">
                                    {{ currentPage }} / {{ totalPages }}
                                </span>

                                <!-- Next -->
                                <Button
                                    variant="outline"
                                    size="sm"
                                    :disabled="currentPage === totalPages"
                                    @click="next"
                                >
                                    Next
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <div>
                <Card class="h-full">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>Pending For Review</CardTitle>
                                <CardDescription>
                                    Items awaiting verification.
                                </CardDescription>
                            </div>

                            <Badge variant="outline">
                                {{ pendingItems.length }}
                            </Badge>
                        </div>
                    </CardHeader>

                    <CardContent class="flex h-full flex-col">
                        <!-- List -->
                        <div
                            v-if="pendingItems.length > 0"
                            class="flex-1 space-y-3 overflow-y-auto pr-1"
                        >
                            <div
                                v-for="item in pendingItems.slice(0, 5)"
                                :key="item.id"
                                class="cursor-pointer rounded-xl border bg-background p-4 transition-all hover:border-primary/30 hover:shadow-sm"
                                @click="viewPendingItem(item.id)"
                            >
                                <!-- Header -->
                                <div
                                    class="flex items-start justify-between gap-3"
                                >
                                    <div class="min-w-0">
                                        <h3
                                            class="truncate text-sm font-semibold"
                                        >
                                            {{ item.title }}
                                        </h3>

                                        <p
                                            class="mt-0.5 text-xs text-muted-foreground"
                                        >
                                            Verification Request #{{ item.id }}
                                        </p>
                                    </div>

                                    <Badge
                                        variant="secondary"
                                        class="shrink-0 whitespace-nowrap"
                                    >
                                        {{ item.status.label }}
                                    </Badge>
                                </div>

                                <!-- Participants -->
                                <div class="mt-4 rounded-lg border bg-muted/30">
                                    <!-- Owner -->
                                    <div
                                        class="flex items-center justify-between border-b px-3 py-2"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500/10"
                                            >
                                                <User
                                                    class="h-4 w-4 text-red-500"
                                                />
                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs text-muted-foreground"
                                                >
                                                    Missing Item Owner
                                                </p>
                                                <p class="text-sm font-medium">
                                                    {{
                                                        item.user?.name ??
                                                        'Unknown User'
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Finder -->
                                    <div
                                        class="flex items-center justify-between px-3 py-2"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500/10"
                                            >
                                                <User
                                                    class="h-4 w-4 text-green-600"
                                                />
                                            </div>

                                            <div>
                                                <p
                                                    class="text-xs text-muted-foreground"
                                                >
                                                    Person Who Found It
                                                </p>
                                                <p class="text-sm font-medium">
                                                    {{
                                                        item.found_report?.user
                                                            ?.name ??
                                                        'Awaiting finder details'
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div
                                    class="mt-4 flex items-center justify-between border-t pt-3 text-xs text-muted-foreground"
                                >
                                    <span>Submitted</span>

                                    <span class="font-medium">
                                        {{
                                            item.found_report?.created_at
                                                ? formatDateTime(
                                                      item.found_report
                                                          .created_at,
                                                  )
                                                : '-'
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- EMPTY STATE -->
                        <div
                            v-else
                            class="flex flex-1 flex-col items-center justify-center rounded-xl border border-dashed bg-muted/10 px-6 py-12 text-center"
                        >
                            <ClipboardCheckIcon
                                class="mb-4 h-10 w-10 text-muted-foreground/60"
                            />

                            <h3 class="text-sm font-semibold">
                                No Pending Verification Requests
                            </h3>
                        </div>

                        <!-- Footer Button -->
                        <div class="mt-4 border-t pt-4">
                            <Button
                                variant="outline"
                                class="w-full"
                                :disabled="pendingItems.length === 0"
                                @click="
                                    router.visit(
                                        '/admin/reported-items/pending-verification',
                                    )
                                "
                            >
                                View All Pending Verification Requests
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
