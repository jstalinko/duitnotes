<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import * as dashboard from '@/routes/dashboard';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { PencilIcon, TrashIcon, PlusIcon } from 'lucide-vue-next';

const props = defineProps(['categories']);

const breadcrumbs = [
    { title: 'Dashboard', href: dashboard.index().url },
    { title: 'Kategori', href: '/dashboard/category' },
];

// --- Modals State ---
const isAddEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const selectedCategoryId = ref(null);

// --- Form State ---
const form = useForm({
    name: '',
});

// --- Actions ---
const openAddModal = () => {
    isEditing.value = false;
    selectedCategoryId.value = null;
    form.reset();
    isAddEditModalOpen.value = true;
};

const openEditModal = (c) => {
    isEditing.value = true;
    selectedCategoryId.value = c.id;
    form.name = c.name;
    isAddEditModalOpen.value = true;
};

const openDeleteModal = (id) => {
    selectedCategoryId.value = id;
    isDeleteModalOpen.value = true;
};

const saveCategory = () => {
    if (isEditing.value && selectedCategoryId.value) {
        form.put(`/dashboard/category/${selectedCategoryId.value}`, {
            onSuccess: () => {
                isAddEditModalOpen.value = false;
                form.reset();
            }
        });
    } else {
        form.post(`/dashboard/category`, {
            onSuccess: () => {
                isAddEditModalOpen.value = false;
                form.reset();
            }
        });
    }
};

const deleteCategory = () => {
    if (selectedCategoryId.value) {
        router.delete(`/dashboard/category/${selectedCategoryId.value}`, {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                selectedCategoryId.value = null;
            }
        });
    }
};

</script>

<template>

    <Head title="Kategori" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-hidden rounded-xl p-4">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Kategori</h1>
                    <p class="text-sm text-muted-foreground">Kelola label kategori transaksi Anda.</p>
                </div>
                <Button @click="openAddModal">
                    <PlusIcon class="mr-2 h-4 w-4" /> Tambah Kategori
                </Button>
            </div>

            <!-- Table -->
            <div class="rounded-md border border-sidebar-border dark:border-sidebar-border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Nama Kategori</TableHead>
                            <TableHead class="text-center w-[100px]">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="c in props.categories.data" :key="c.id">
                            <TableCell class="capitalize">{{ c.name }}</TableCell>
                            <TableCell class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-blue-500"
                                        @click="openEditModal(c)">
                                        <PencilIcon class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-red-500"
                                        @click="openDeleteModal(c.id)">
                                        <TrashIcon class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="props.categories.data.length === 0">
                            <TableCell colspan="2" class="h-24 text-center">
                                Belum ada kategori yang dibuat.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

        </div>

        <!-- Add/Edit Modal -->
        <Dialog v-model:open="isAddEditModalOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}</DialogTitle>
                    <DialogDescription>
                        Kelola nama kategori transaksi Anda.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveCategory">
                    <div class="grid gap-4 py-4">
                        <div class="grid gap-2">
                            <Label for="name">Nama Kategori</Label>
                            <Input id="name" v-model="form.name" placeholder="Contoh: Makanan, Transportasi" required />
                            <span v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</span>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="isAddEditModalOpen = false">Batal</Button>
                        <Button type="submit" :disabled="form.processing">Simpan</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Modal -->
        <AlertDialog v-model:open="isDeleteModalOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Apakah Anda yakin?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Tindakan ini tidak dapat dibatalkan. Kategori yang dihapus akan terhapus secara permanen.
                        Pastikan tidak ada transaksi yang terkait atau pindahkan datanya dahulu.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="isDeleteModalOpen = false">Batal</AlertDialogCancel>
                    <AlertDialogAction @click="deleteCategory" class="bg-red-500 hover:bg-red-600">Hapus
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

    </AppLayout>
</template>