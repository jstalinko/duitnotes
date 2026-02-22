<script setup>
import { MessageSquareText, Image as ImageIcon, LayoutDashboard, FileSpreadsheet, Send, Zap, Smartphone } from 'lucide-vue-next';

const features = [
    {
        title: 'Tinggal Chat, Beres!',
        description: 'Tidak perlu buka aplikasi rumit. Cukup ketik "Makan siang 50rb", AI kami otomatis mencatatnya sebagai pengeluaran.',
        icon: MessageSquareText,
        color: 'text-cyan-400',
        bg: 'bg-cyan-500/10'
    },
    {
        title: 'Foto Struk Langsung Jadi',
        description: 'Habis belanja? Kirim saja foto struk atau tagihan ke nomor WhatsApp kami, nominal dan kategori otomatis tercatat.',
        icon: ImageIcon,
        color: 'text-purple-400',
        bg: 'bg-purple-500/10'
    },
    {
        title: 'Dashboard & Statistik Lengkap',
        description: 'Pantau arus kas, pemasukan, dan pengeluaran Anda melalui grafik visual yang mudah dipahami di dashboard web.',
        icon: LayoutDashboard,
        color: 'text-blue-400',
        bg: 'bg-blue-500/10'
    },
    {
        title: 'Minta Rekap via WhatsApp',
        description: 'Mau tahu total pengeluaran bulan ini? Cukup chat "Rekap bulan ini", bot akan langsung membalas rinciannya.',
        icon: Send,
        color: 'text-green-400',
        bg: 'bg-green-500/10'
    },
    {
        title: 'Export Excel & PDF',
        description: 'Butuh laporan rapi untuk pembukuan atau atasan? Download rekap keuangan Anda dalam format Excel atau PDF 1-klik.',
        icon: FileSpreadsheet,
        color: 'text-orange-400',
        bg: 'bg-orange-500/10'
    },
    {
        title: 'Sangat Mudah Digunakan',
        description: 'Didesain untuk semua orang. Tanpa perlu belajar akuntansi, Anda sudah bisa punya asisten keuangan pribadi super pintar.',
        icon: Zap,
        color: 'text-yellow-400',
        bg: 'bg-yellow-500/10'
    }
];
</script>

<template>
    <section id="features" class="relative py-24 bg-slate-950 overflow-hidden">
        <!-- Ambient Background Glows -->
        <div
            class="absolute top-1/4 left-0 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 right-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px] pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-20 animate-in fade-in slide-in-from-bottom-5 duration-700">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-400 text-sm font-medium mb-4">
                    <Smartphone class="w-4 h-4" />
                    Kenapa DuitNotes?
                </div>
                <h2
                    class="text-3xl md:text-5xl font-extrabold text-white mb-6 tracking-tight max-w-3xl mx-auto leading-tight">
                    Catat Keuangan Sehari-hari,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                        Tanpa Ribet Buka Aplikasi
                    </span>
                </h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    Ucapkan selamat tinggal pada aplikasi keuangan yang rumit. Hubungkan nomor Anda, dan mulai kontrol
                    dompet dengan cara yang paling Anda kuasai: chatting.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <div v-for="(feature, index) in features" :key="index"
                    class="group relative p-8 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 hover:bg-white/10 hover:border-white/20 hover:shadow-[0_8px_30px_rgba(0,0,0,0.5)] animate-in fade-in slide-in-from-bottom-10 fill-mode-both duration-1000"
                    :style="{ animationDelay: `${index * 100}ms` }">
                    <div
                        :class="['w-14 h-14 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3', feature.bg]">
                        <component :is="feature.icon" :class="['w-7 h-7', feature.color]" stroke-width="2" />
                    </div>

                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-cyan-300 transition-colors">{{
                        feature.title }}</h3>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        {{ feature.description }}
                    </p>
                </div>
            </div>

            <!-- Bottom Call Out -->
            <div class="mt-20 text-center animate-in fade-in slide-in-from-bottom-10 duration-1000 delay-500">
                <div
                    class="inline-block p-1 rounded-full bg-gradient-to-r from-cyan-500/30 via-purple-500/30 to-blue-500/30 backdrop-blur-md">
                    <div
                        class="flex items-center gap-4 px-6 py-3 rounded-full bg-slate-900 border border-white/10 shadow-xl">
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-slate-900 bg-cyan-500 flex items-center justify-center text-xs font-bold text-white">
                                1</div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-slate-900 bg-blue-500 flex items-center justify-center text-xs font-bold text-white">
                                2</div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-slate-900 bg-purple-500 flex items-center justify-center text-xs font-bold text-white">
                                3</div>
                        </div>
                        <p class="text-sm font-medium text-slate-300">Bergabunglah dengan <span
                                class="text-white font-bold">100+</span> pengguna lainnya hari ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>