<script setup>
import { ref } from 'vue';
import { ChevronDown, MessageCircleQuestion } from 'lucide-vue-next';

const faqs = [
    {
        question: 'Apakah DuitNotes aman digunakan?',
        answer: 'Sangat aman. Kami menggunakan enkripsi kelas industri untuk melindungi data transaksi Anda. Server kami mematuhi standar keamanan tertinggi dan kami tidak pernah membagikan atau menjual data finansial Anda ke pihak ketiga.',
    },
    {
        question: 'Apakah saya bisa mengubah nomor WhatsApp yang terdaftar?',
        answer: 'Tentu bisa. Anda dapat mengubah nomor WhatsApp yang terhubung langsung melalui menu pengaturan di dashboard web setelah Anda login ke akun DuitNotes Anda.',
    },
    {
        question: 'Bagaimana cara kerja upload struk belanja?',
        answer: 'Sangat mudah! Cukup foto struk atau tagihan Anda, lalu kirimkan foto tersebut ke nomor bot WhatsApp DuitNotes. AI mutakhir kami akan langsung "membaca" gambar tersebut dan mengekstrak nominal, tipe transaksi, dan membuat laporannya secara otomatis.',
    },
    {
        question: 'Apakah ada batasan fitur untuk pengguna gratis (Free Tier)?',
        answer: 'Pengguna gratis dapat mencatat hingga 3 transaksi per hari dan mendapatkan rekap harian via WhatsApp. Untuk batas pencatatan unlimited, deteksi gambar/struk, dan akses export PDF/Excel, Anda dapat melakukan upgrade ke Starter Tier.',
    },
    {
        question: 'Bagaimana jika AI salah mencatat nominal/kategori?',
        answer: 'Anda bisa mengeditnya di dashboard DuitNotes, atau Anda juga bisa menghapusnya.',
    },
    {
        question: 'Apakah bisa menambah catatan keuangan di dashboard saja?',
        answer: 'Tentu sangat bisa! Jika Anda malas membuka WhatsApp, buka saja dashboard untuk catat keuangan Anda.',
    },
    {
        question: 'Apakah jika saya langganan keluarga saya juga bisa pakai?',
        answer: 'Tidak. Keluarga Anda juga harus langganan. Pada dasarnya DuitNotes satu nomer HP / WhatsApp untuk satu akun.',
    },
];

const activeFaq = ref(null);

const toggleFaq = (index) => {
    activeFaq.value = activeFaq.value === index ? null : index;
};
</script>

<template>
    <section id="faq" class="relative py-24 bg-slate-950 overflow-hidden">
        <!-- Ambient Glow -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="container mx-auto px-4 relative z-10 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-16 animate-in fade-in slide-in-from-bottom-5 duration-700">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-400 text-sm font-medium mb-4">
                    <MessageCircleQuestion class="w-4 h-4" />
                    Bantuan
                </div>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">
                    Pertanyaan Seputar <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">DuitNotes</span>
                </h2>
                <p class="text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    Jawaban cepat untuk pertanyaan yang paling sering diajukan pelanggan.
                </p>
            </div>

            <!-- FAQ Accordion -->
            <div class="space-y-4">
                <div v-for="(faq, index) in faqs" :key="index"
                    class="group rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm overflow-hidden transition-all duration-300 animate-in fade-in slide-in-from-bottom-10 fill-mode-both"
                    :class="[activeFaq === index ? 'bg-white/10 border-cyan-500/30' : 'hover:bg-white/10 hover:border-white/20']"
                    :style="{ animationDelay: `${index * 100}ms` }">
                    <button @click="toggleFaq(index)"
                        class="flex w-full items-center justify-between p-6 text-left focus:outline-none">
                        <span class="text-lg font-bold transition-colors duration-300"
                            :class="[activeFaq === index ? 'text-cyan-400' : 'text-white group-hover:text-cyan-300']">
                            {{ faq.question }}
                        </span>
                        <div class="ml-4 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/5 transition-transform duration-300"
                            :class="[activeFaq === index ? 'rotate-180 bg-cyan-500/20 text-cyan-400' : 'text-slate-400']">
                            <ChevronDown class="h-5 w-5" />
                        </div>
                    </button>

                    <div class="overflow-hidden transition-all duration-300 ease-in-out"
                        :style="[activeFaq === index ? 'max-height: 200px; opacity: 1;' : 'max-height: 0px; opacity: 0;']">
                        <div class="px-6 pb-6 text-slate-400 leading-relaxed border-t border-white/5 pt-4">
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>