export type Permission = {
    id: number;
    name: string;

    status: 0 | 1;
    status_label: string;

    permission_group_id: number | null;

    permission_group?: {
        id: number;
        name: string;
    };

    created_at: string;
    updated_at: string;
};
