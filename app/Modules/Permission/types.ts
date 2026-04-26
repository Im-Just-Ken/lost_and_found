export type Permission = {
    id: number;
    name: string;

    status: 0 | 1;
    status_label: string;

    group_id: number | null;

    group?: {
        id: number;
        name: string;
    };

    created_at: string;
    updated_at: string;
};
