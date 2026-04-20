import { BadgeVariants } from '@/components/ui/badge';

type StatusPayload = {
    value: number;
    label: string;
    key: string;
};

const map: Record<number, BadgeVariants['variant']> = {
    1: 'success',
    0: 'destructive',
};

export function useStatus() {
    function get(status: StatusPayload) {
        return {
            label: status.label,
            variant: map[status.value] ?? 'secondary',
        };
    }

    return { get };
}
