<script setup lang="ts">
import { reactive, ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogDescription,
    AlertDialogFooter,
} from '@/components/ui/alert-dialog';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

// PROPS
const props = defineProps<{
    permissions: Array<any>;
}>();

// SEARCH
const search = ref('');

// ERRORS
const errors = reactive({
    name: '',
});

// FILTERED DATA
const filteredPermissions = computed(() => {
    if (!search.value) return props.permissions;

    return props.permissions.filter((perm) =>
        perm.name.toLowerCase().includes(search.value.toLowerCase()),
    );
});

// MODAL STATE
const open = ref(false);

// EDIT MODE
const isEditing = ref(false);
const editingId = ref<number | null>(null);

// DELETE STATE
const selectedPermission = ref<{ id: number; name: string } | null>(null);
const deleteOpen = ref(false);

// FORM
const form = reactive({
    name: '',
});

// OPEN CREATE
function openCreate() {
    form.name = '';
    errors.name = '';
    isEditing.value = false;
    editingId.value = null;
    open.value = true;
}

// OPEN EDIT
function openEdit(permission: any) {
    form.name = permission.name;
    isEditing.value = true;
    editingId.value = permission.id;
    open.value = true;
}

// SUBMIT (CREATE / UPDATE)
function submit() {
    errors.name = '';

    if (!form.name.trim()) {
        errors.name = 'Permission name is required';
        toast.error('Permission name is required');
        return;
    }

    const payload = { ...form };

    if (isEditing.value && editingId.value) {
        router.put(`/permissions/${editingId.value}`, payload, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Permission updated successfully');
                reset();
            },
            onError: (errors) => {
                if (errors.name) {
                    toast.error(errors.name);
                }
            },
        });
    } else {
        router.post('/permissions', payload, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Permission created successfully');
                reset();
            },
            onError: (errors) => {
                if (errors.name) {
                    toast.error(errors.name);
                }
            },
        });
    }
}

// CONFIRM DELETE
function confirmDelete(permission: any) {
    selectedPermission.value = permission;
    deleteOpen.value = true;
}

// DELETE
function destroy() {
    if (!selectedPermission.value) return;

    router.delete(`/permissions/${selectedPermission.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Permission deleted successfully');
            deleteOpen.value = false;
            selectedPermission.value = null;
        },
        onError: () => {
            toast.error('Failed to delete permission');
        },
    });
}

// RESET FORM
function reset() {
    form.name = '';
    open.value = false;
    isEditing.value = false;
    editingId.value = null;
}

// CLEAR ERROR ON TYPE
watch(
    () => form.name,
    () => {
        errors.name = '';
    },
);
</script>

<template>
    <div class="space-y-6 p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Permissions</h1>
            <p class="text-sm text-muted-foreground">
                Manage system permissions for roles and users
            </p>
        </div>

        <div class="flex items-center justify-between gap-4">
            <div class="w-72">
                <Input v-model="search" placeholder="Search permissions..." />
            </div>

            <Button @click="openCreate">Create Permission</Button>
        </div>

        <div class="rounded-lg border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead class="w-[160px] text-right">
                            Actions
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow
                        v-for="perm in filteredPermissions"
                        :key="perm.id"
                    >
                        <TableCell class="font-medium">
                            {{ perm.name }}
                        </TableCell>

                        <TableCell class="space-x-2 text-right">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="openEdit(perm)"
                            >
                                Edit
                            </Button>

                            <Button
                                variant="destructive"
                                size="sm"
                                @click="confirmDelete(perm)"
                            >
                                Delete
                            </Button>
                        </TableCell>
                    </TableRow>

                    <TableRow v-if="filteredPermissions.length === 0">
                        <TableCell
                            colspan="2"
                            class="py-6 text-center text-muted-foreground"
                        >
                            No results found.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <Dialog v-model:open="open">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>
                        {{
                            isEditing ? 'Edit Permission' : 'Create Permission'
                        }}
                    </DialogTitle>
                </DialogHeader>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <Input
                            v-model="form.name"
                            placeholder="e.g. event.delete"
                        />

                        <p v-if="errors.name" class="text-sm text-destructive">
                            {{ errors.name }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button variant="outline" @click="reset">
                            Cancel
                        </Button>

                        <Button @click="submit">
                            {{ isEditing ? 'Update' : 'Save' }}
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>

        <AlertDialog v-model:open="deleteOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Delete Permission</AlertDialogTitle>

                    <AlertDialogDescription>
                        This action cannot be undone. This will permanently
                        delete
                        <span class="font-semibold text-foreground">
                            {{ selectedPermission?.name }} </span
                        >.
                    </AlertDialogDescription>
                </AlertDialogHeader>

                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>

                    <AlertDialogAction
                        @click="destroy"
                        class="bg-destructive text-white hover:bg-destructive/90"
                    >
                        Delete
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
