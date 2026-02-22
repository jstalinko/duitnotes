<script setup>
import { Check } from 'lucide-vue-next';

const plans = [
    {
        name: 'Free Tier',
        price: 'Gratis',
        period: '/selamanya',
        description: 'Mulai mencatat keuangan semudah chating.',
        features: [
            'Auto detect chat text transaction',
            '3 catatan transaksi per hari',
            'Rekap via WhatsApp',
        ],
        highlight: false,
        buttonText: 'Mulai Gratis',
        buttonClass: 'bg-white/5 border border-white/10 text-white hover:bg-white/10',
    },
    {
        name: 'Starter Tier',
        price: 'Rp 60.000',
        period: '/bulan',
        description: 'Fitur lengkap untuk personal & bisnis.',
        features: [
            'Auto detect chat text transaction',
            'Auto detect gambar struk / invoice',
            'Unlimited catatan transaksi',
            'Rekap via WhatsApp',
            'Login dashboard',
            'Export Excel & PDF',
            'Statistik lengkap',
        ],
        highlight: true,
        badge: 'Paling Populer',
        buttonText: 'Berlangganan Starter',
        buttonClass: 'bg-gradient-to-r from-cyan-600 to-blue-600 text-white hover:from-cyan-500 hover:to-blue-500 shadow-[0_0_20px_rgba(8,145,178,0.4)]',
    },
    {
        name: 'Plus Tier',
        price: 'Rp 110.000',
        period: '/3 bulan',
        description: 'Lebih hemat untuk pemakaian jangka panjang.',
        features: [
            'Semua fitur Starter Tier',
            'Auto detect chat text transaction',
            'Auto detect gambar struk / invoice',
            'Unlimited catatan transaksi',
            'Rekap via WhatsApp',
            'Login dashboard',
            'Export Excel & PDF',
            'Statistik lengkap',
        ],
        highlight: false,
        buttonText: 'Berlangganan Plus',
        buttonClass: 'bg-white/5 border border-white/10 text-white hover:bg-white/10',
    }
];
</script>

<template>
    <section id="pricing" class="relative py-24 bg-slate-950 overflow-hidden">
        <!-- Ambient Background Glows -->
        <div
            class="absolute top-0 inset-x-0 h-px w-full bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20 animate-in fade-in slide-in-from-bottom-5 duration-700">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">Investasi Super Murah
                </h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">Pilih paket yang sesuai dengan
                    kebutuhan pencatatan keuangan Anda. Upgrade kapan saja tanpa ribet.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto items-center">
                <div v-for="(plan, index) in plans" :key="index" :class="[
                    'relative p-8 rounded-[2rem] border transition-all duration-300 hover:-translate-y-2 backdrop-blur-xl',
                    plan.highlight
                        ? 'bg-slate-900 border-cyan-500/50 shadow-[0_0_40px_rgba(8,145,178,0.15)] lg:scale-105 z-10'
                        : 'bg-white/5 border-white/10'
                ]" :style="{ animationDelay: `${index * 150}ms` }"
                    class="animate-in fade-in slide-in-from-bottom-10 fill-mode-both duration-1000">
                    <!-- Highlight Badge -->
                    <div v-if="plan.badge"
                        class="absolute -top-4 left-1/2 -translate-x-1/2 uppercase tracking-wider text-xs font-extrabold px-4 py-1.5 bg-gradient-to-r from-cyan-400 to-blue-500 text-white rounded-full shadow-lg">
                        {{ plan.badge }}
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-white mb-2">{{ plan.name }}</h3>
                        <p class="text-sm text-slate-400 min-h-[40px]">{{ plan.description }}</p>
                    </div>

                    <div class="mb-8">
                        <div class="flex items-baseline gap-1">
                            <span
                                class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-white to-slate-400">{{
                                plan.price }}</span>
                            <span class="text-slate-400 font-medium">{{ plan.period }}</span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li v-for="(feature, idx) in plan.features" :key="idx" class="flex items-start gap-3">
                            <div
                                class="mt-0.5 md:mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-cyan-500/20">
                                <Check class="w-3.5 h-3.5 text-cyan-400" stroke-width="3" />
                            </div>
                            <span class="text-slate-300 text-sm md:text-base leading-tight">{{ feature }}</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-4 border-t border-white/10">
                        <button :class="[
                            'w-full py-4 mt-2 rounded-2xl font-bold transition-all duration-200 active:scale-95 text-base',
                            plan.buttonClass
                        ]">
                            {{ plan.buttonText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>