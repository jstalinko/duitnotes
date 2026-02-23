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
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
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

const props = defineProps(['transactions']);

const breadcrumbs = [
    { title: 'Dashboard', href: dashboard.index().url },
    { title: 'Duit Notes', href: dashboard.duitNotes().url },
];

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};

// --- Modals State ---
const isAddEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isEditing = ref(false);
const selectedTransactionId = ref(null);

// --- Form State ---
const form = useForm({
    amount: null,
    type: 'out',
    description: '',
    category_name: '',
});

// --- Actions ---
const openAddModal = () => {
    isEditing.value = false;
    selectedTransactionId.value = null;
    form.reset();
    isAddEditModalOpen.value = true;
};

const openEditModal = (t) => {
    isEditing.value = true;
    selectedTransactionId.value = t.id;
    form.amount = t.amount;
    form.type = t.type;
    form.description = t.description;
    form.category_name = t.category?.name || '';
    isAddEditModalOpen.value = true;
};

const openDeleteModal = (id) => {
    selectedTransactionId.value = id;
    isDeleteModalOpen.value = true;
};

const saveTransaction = () => {
    if (isEditing.value && selectedTransactionId.value) {
        form.put(`/dashboard/duit-notes/${selectedTransactionId.value}`, {
            onSuccess: () => {
                isAddEditModalOpen.value = false;
                form.reset();
            }
        });
    } else {
        form.post(`/dashboard/duit-notes`, {
            onSuccess: () => {
                isAddEditModalOpen.value = false;
                form.reset();
            }
        });
    }
};

const deleteTransaction = () => {
    if (selectedTransactionId.value) {
        router.delete(`/dashboard/duit-notes/${selectedTransactionId.value}`, {
            onSuccess: () => {
                isDeleteModalOpen.value = false;
                selectedTransactionId.value = null;
            }
        });
    }
};

</script>

<template>

    <Head title="Duit Notes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-hidden rounded-xl p-4">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Duit Notes</h1>
                    <p class="text-sm text-muted-foreground">Catat pemasukan dan pengeluaran kamu di sini.</p>
                </div>
                <Button @click="openAddModal">
                    <PlusIcon class="mr-2 h-4 w-4" /> Tambah Catatan
                </Button>
            </div>

            <!-- Table -->
            <div class="rounded-md border border-sidebar-border dark:border-sidebar-border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Tanggal</TableHead>
                            <TableHead>Deskripsi</TableHead>
                            <TableHead>Kategori</TableHead>
                            <TableHead>Tipe</TableHead>
                            <TableHead class="text-right">Jumlah</TableHead>
                            <TableHead class="text-center w-[100px]">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="t in props.transactions.data" :key="t.id">
                            <TableCell>{{ new Date(t.created_at).toLocaleDateString() }}</TableCell>
                            <TableCell>{{ t.description }}</TableCell>
                            <TableCell>
                                <span class="rounded-full px-2 py-1 text-xs"
                                    :class="t.type === 'in' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500'">
                                    {{ t.category?.name || '-' }}
                                </span>
                            </TableCell>
                            <TableCell class="uppercase text-xs">{{ t.type }}</TableCell>
                            <TableCell class="text-right font-medium"
                                :class="t.type === 'in' ? 'text-green-500' : 'text-red-500'">
                                {{ t.type === 'in' ? '+' : '-' }}{{ formatCurrency(t.amount) }}
                            </TableCell>
                            <TableCell class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-blue-500"
                                        @click="openEditModal(t)">
                                        <PencilIcon class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" class="h-8 w-8 text-red-500"
                                        @click="openDeleteModal(t.id)">
                                        <TrashIcon class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="props.transactions.data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center">
                                Belum ada catatan transaksi.
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
                    <DialogTitle>{{ isEditing ? 'Edit Catatan' : 'Tambah Catatan' }}</DialogTitle>
                    <DialogDescription>
                        Aktivitas transaksi keuangan Anda.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveTransaction">
                    <div class="grid gap-4 py-4">
                        <!-- Tipe -->
                        <div class="grid gap-2">
                            <Label for="type">Tipe Transaksi</Label>
                            <Select v-model="form.type">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih tipe" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="out">Pengeluaran (Out)</SelectItem>
                                        <SelectItem value="in">Pemasukan (In)</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <span v-if="form.errors.type" class="text-xs text-red-500">{{ form.errors.type }}</span>
                        </div>

                        <!-- Jumlah -->
                        <div class="grid gap-2">
                            <Label for="amount">Jumlah (Rp)</Label>
                            <Input id="amount" type="number" v-model="form.amount" placeholder="50000" min="0"
                                required />
                            <span v-if="form.errors.amount" class="text-xs text-red-500">{{ form.errors.amount }}</span>
                        </div>

                        <!-- Deskripsi -->
                        <div class="grid gap-2">
                            <Label for="description">Deskripsi</Label>
                            <Input id="description" v-model="form.description" placeholder="Makan siang" required />
                            <span v-if="form.errors.description" class="text-xs text-red-500">{{ form.errors.description
                            }}</span>
                        </div>

                        <!-- Kategori -->
                        <div class="grid gap-2">
                            <Label for="category">Kategori</Label>
                            <Input id="category" v-model="form.category_name" placeholder="Makanan" />
                            <span class="text-xs text-muted-foreground">Kategori baru akan otomatis dibuat jika belum
                                ada.</span>
                            <span v-if="form.errors.category_name" class="text-xs text-red-500">{{
                                form.errors.category_name }}</span>
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
                        Tindakan ini tidak dapat dibatalkan. Catatan transaksi akan dihapus secara permanen dari server.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel @click="isDeleteModalOpen = false">Batal</AlertDialogCancel>
                    <AlertDialogAction @click="deleteTransaction" class="bg-red-500 hover:bg-red-600">Hapus
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>

    </AppLayout>
</template>