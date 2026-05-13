// resources/js/composables/useItemProgress.ts

import { computed } from 'vue';
import { ItemStatus } from '@/generated/enums';

export function useItemProgress(item: any) {
    /**
     * PROGRESS STEPS
     */
    const progressSteps = [
        {
            label: 'Reported',
            status: ItemStatus.LOST,
        },
        {
            label: 'Found (For Review)',
            status: ItemStatus.FOUND_PENDING,
        },
        {
            label: 'Found',
            status: ItemStatus.FOUND,
        },
        {
            label: 'Claimed',
            status: ItemStatus.CLAIMED,
        },
    ];

    /**
     * CURRENT STEP
     */
    const currentStep = computed(() => {
        switch (item.status?.value) {
            case ItemStatus.CLAIMED:
                return 3;

            case ItemStatus.FOUND:
                return 2;

            case ItemStatus.FOUND_PENDING:
                return 1;

            case ItemStatus.LOST:
            default:
                return 0;
        }
    });

    /**
     * PROGRESS WIDTH
     */
    const progressWidth = computed(() => {
        return `${(currentStep.value / (progressSteps.length - 1)) * 100}%`;
    });

    return {
        progressSteps,
        currentStep,
        progressWidth,
    };
}
