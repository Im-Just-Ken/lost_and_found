<script setup lang="ts">
import { computed } from 'vue';
import VChart from 'vue-echarts';

import { use } from 'echarts/core';
import { PieChart } from 'echarts/charts';
import {
    TooltipComponent,
    LegendComponent,
    TitleComponent,
} from 'echarts/components';
import { CanvasRenderer } from 'echarts/renderers';

use([
    PieChart,
    TooltipComponent,
    LegendComponent,
    TitleComponent,
    CanvasRenderer,
]);

const props = defineProps<{
    missing: number;
    pendingReview: number;
    found: number;
    claimed: number;
}>();

const total = computed(
    () => props.missing + props.pendingReview + props.found + props.claimed,
);

const getPercentage = (value: number) => {
    if (total.value === 0) return 0;
    return Number(((value / total.value) * 100).toFixed(1));
};

const chartData = computed(() => [
    {
        value: props.missing,
        name: 'Missing',
    },
    {
        value: props.pendingReview,
        name: 'Pending Review',
    },
    {
        value: props.found,
        name: 'Found',
    },
    {
        value: props.claimed,
        name: 'Claimed',
    },
]);

const option = computed(() => ({
    tooltip: {
        trigger: 'item',
        formatter: '{b}: {c} ({d}%)',
    },

    legend: {
        orient: 'vertical',
        right: 10,
        top: 'center',
        formatter: (name: string) => {
            const item = chartData.value.find((item) => item.name === name);

            if (!item) {
                return name;
            }

            return `${name} (${item.value} • ${getPercentage(item.value)}%)`;
        },
    },

    series: [
        {
            name: 'Items',
            type: 'pie',
            radius: ['45%', '75%'],

            label: {
                formatter: '{b}\n{d}%',
            },

            data: chartData.value,
        },
    ],
}));
</script>

<template>
    <VChart class="h-auto w-full" :option="option" autoresize />
</template>
