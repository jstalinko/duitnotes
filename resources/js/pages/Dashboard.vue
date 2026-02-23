<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import * as dashboard from '@/routes/dashboard';
import { computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import type { ApexOptions } from 'apexcharts';

// Define Props from Controller
const props = defineProps<{
    transactions: any;
    totalTransactions: number;
    totalIncome: number;
    totalOutcome: number;
    totalBalance: number;
    expenseByCategory: { category: string; total: number }[];
    incomeByCategory: { category: string; total: number }[];
    dailyTransactions: { date: string; type: string; total: number }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard.index().url,
    },
];

// Helper to format currency
const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};

// --- Pie Chart Config (Expense by Category) ---
const pieChartOptions = computed<ApexOptions>(() => ({
    chart: { type: 'pie', background: 'transparent' },
    labels: props.expenseByCategory.map(item => item.category),
    theme: { mode: 'dark' },
    dataLabels: { enabled: true },
    tooltip: { theme: 'dark' },
    legend: { position: 'bottom' }
}));
const pieChartSeries = computed(() => props.expenseByCategory.map(item => item.total));

// --- Bar/Line Chart Config (Daily Transactions last 30 days) ---
const barChartOptions = computed<ApexOptions>(() => {
    // Collect unique dates
    const dates = [...new Set(props.dailyTransactions.map(t => t.date))];
    return {
        chart: {
            type: 'bar',
            background: 'transparent',
            toolbar: { show: false }
        },
        xaxis: { categories: dates },
        colors: ['#10b981', '#ef4444'], // Green for in, Red for out
        theme: { mode: 'dark' },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } }
    };
});

const barChartSeries = computed(() => {
    const dates = [...new Set(props.dailyTransactions.map(t => t.date))];

    // Series for 'in' (Income)
    const dataIn = dates.map(date => {
        const found = props.dailyTransactions.find(t => t.date === date && t.type === 'in');
        return found ? found.total : 0;
    });

    // Series for 'out' (Expense)
    const dataOut = dates.map(date => {
        const found = props.dailyTransactions.find(t => t.date === date && t.type === 'out');
        return found ? found.total : 0;
    });

    return [
        { name: 'Pemasukan', data: dataIn },
        { name: 'Pengeluaran', data: dataOut }
    ];
});
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-hidden rounded-xl p-4">

            <!-- Summary Cards Overview -->
            <div class="grid gap-4 md:grid-cols-4">
                <div
                    class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <span class="text-sm text-muted-foreground">Total Balance</span>
                    <span class="text-2xl font-bold"
                        :class="props.totalBalance >= 0 ? 'text-green-500' : 'text-red-500'">{{
                            formatCurrency(props.totalBalance) }}</span>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <span class="text-sm text-muted-foreground">Total Income</span>
                    <span class="text-2xl font-bold text-green-500">{{ formatCurrency(props.totalIncome) }}</span>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <span class="text-sm text-muted-foreground">Total Expense</span>
                    <span class="text-2xl font-bold text-red-500">{{ formatCurrency(props.totalOutcome) }}</span>
                </div>
                <div
                    class="flex flex-col gap-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <span class="text-sm text-muted-foreground">Total Transactions</span>
                    <span class="text-2xl font-bold text-foreground">{{ props.totalTransactions }}</span>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid gap-4 md:grid-cols-3">

                <!-- Pie Chart: Expense by Category -->
                <div
                    class="col-span-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <h3 class="font-semibold text-lg mb-4 text-foreground">Pengeluaran - Kategori</h3>
                    <div v-if="props.expenseByCategory.length > 0">
                        <VueApexCharts type="pie" :options="pieChartOptions" :series="pieChartSeries" />
                    </div>
                    <div v-else class="text-center text-muted-foreground py-10">Belum ada data pengeluaran.</div>
                </div>

                <!-- Bar Chart: Income vs Expense last 30 Days -->
                <div
                    class="col-span-2 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                    <h3 class="font-semibold text-lg mb-4 text-foreground">Aktivitas 30 Hari Terakhir</h3>
                    <div v-if="props.dailyTransactions.length > 0">
                        <VueApexCharts type="bar" height="300" :options="barChartOptions" :series="barChartSeries" />
                    </div>
                    <div v-else class="text-center text-muted-foreground py-20">Belum ada transaksi di 30 hari
                        terakhir.</div>
                </div>

            </div>

            <!-- Recent Transactions Table -->
            <div class="rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border dark:bg-sidebar">
                <h3 class="font-semibold text-lg mb-4 text-foreground">Transaksi Terakhir</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-muted-foreground">
                        <thead class="bg-sidebar-accent/50 text-xs uppercase text-foreground">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tipe</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="t in props.transactions.data" :key="t.id"
                                class="border-b border-sidebar-border/50">
                                <td class="px-4 py-3">{{ new Date(t.created_at).toLocaleDateString() }}</td>
                                <td class="px-4 py-3">{{ t.description }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs"
                                        :class="t.type === 'in' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500'">
                                        {{ t.category?.name || '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 uppercase">{{ t.type }}</td>
                                <td class="px-4 py-3 text-right font-medium"
                                    :class="t.type === 'in' ? 'text-green-500' : 'text-red-500'">
                                    {{ t.type === 'in' ? '+' : '-' }}{{ formatCurrency(t.amount) }}
                                </td>
                            </tr>
                            <tr v-if="props.transactions.data.length === 0">
                                <td colspan="5" class="py-4 text-center">Belum ada transaksi.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
