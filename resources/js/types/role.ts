export type Role = {
    id: number;
    name: string;

    status: number;
    status_label: string;

    group_id: number | null;

    role_group?: {
        id: number;
        name: string;
    };

    created_at: string;
    updated_at: string;
};
