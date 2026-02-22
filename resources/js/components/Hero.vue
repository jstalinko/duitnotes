<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRight, MessageCircle } from 'lucide-vue-next';
</script>

<template>
    <section class="relative min-h-screen pt-32 pb-20 overflow-hidden bg-slate-950 flex items-center">
        <!-- Ambient Glowing Background Elements -->
        <div
            class="absolute top-1/2 left-1/4 -translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-cyan-600/30 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute top-1/2 right-1/4 -translate-y-1/2 translate-x-1/2 w-[30rem] h-[30rem] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full h-1/2 bg-gradient-to-t from-slate-950 to-transparent z-10 pointer-events-none">
        </div>

        <div class="container mx-auto px-4 sm:px-6 relative z-20">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left animate-in fade-in slide-in-from-bottom-10 duration-1000">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-cyan-300 text-sm font-medium mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-cyan-400 animate-pulse"></span>
                        AI Powered Keuangan
                    </div>

                    <h1
                        class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.1] mb-6">
                        Catat Keuangan <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500">
                            Semudah Chatting
                        </span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-400 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Cukup kirim foto struk atau ketik transaksi Anda via WhatsApp, AI kami akan otomatis mencatat,
                        mengkategorikan, dan menyajikan laporan keuangan Anda.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <Link href="/register"
                            class="group relative inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-white transition-all bg-gradient-to-r from-cyan-600 to-blue-600 rounded-full hover:from-cyan-500 hover:to-blue-500 hover:shadow-[0_0_40px_rgba(8,145,178,0.4)] active:scale-95">
                            Coba Gratis Sekarang
                            <ArrowRight class="w-5 h-5 transition-transform group-hover:translate-x-1" />
                        </Link>

                        <a href="#demo"
                            class="group inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-medium text-white transition-all bg-white/5 border border-white/10 rounded-full hover:bg-white/10 active:scale-95">
                            <MessageCircle class="w-5 h-5 text-slate-300 group-hover:text-white transition-colors" />
                            Lihat Demo
                        </a>
                    </div>
                </div>

                <!-- Visual Mockup -->
                <div
                    class="relative mx-auto w-full max-w-[500px] lg:max-w-none animate-in fade-in slide-in-from-right-10 duration-1000 delay-200">
                    <!-- Outer Glass Container -->
                    <div
                        class="relative rounded-[2rem] border border-white/10 bg-white/5 p-4 shadow-2xl backdrop-blur-xl animate-[float_6s_ease-in-out_infinite]">
                        <!-- Inner Mockup -->
                        <div class="overflow-hidden rounded-[1.5rem] border border-white/10 bg-slate-900 shadow-inner">
                            <img src="/duitnotes.png" alt="DuitNotes App Preview"
                                class="w-full h-auto object-cover transform transition-transform duration-700 hover:scale-105" />
                        </div>

                        <!-- Floating Badges -->
                        <div
                            class="absolute -left-6 top-1/4 rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-md shadow-xl animate-[float_5s_ease-in-out_infinite_reverse]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500/20 text-green-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400">Pemasukan</p>
                                    <p class="text-sm font-bold text-white">+ Rp 5.000.000</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="absolute -right-6 bottom-1/4 rounded-2xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-md shadow-xl animate-[float_7s_ease-in-out_infinite]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/20 text-cyan-400">
                                    <MessageCircle class="h-5 w-5" />
                                </div>
                                <div class="pr-2">
                                    <p class="text-xs text-slate-400">WhatsApp OCR</p>
                                    <p class="text-sm font-bold text-white">Struk Terbaca ✓</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes float {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-20px);
    }
}
</style>