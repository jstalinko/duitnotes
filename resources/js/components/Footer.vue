<script setup>
import { Link } from '@inertiajs/vue3';
import { Twitter, Instagram, Github } from 'lucide-vue-next';
</script>

<template>
    <footer class="bg-slate-950 border-t border-white/10 pt-16 pb-8">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-8 mb-12">

                <!-- Brand -->
                <div class="md:col-span-2">
                    <Link href="/" class="flex items-center gap-2 mb-4 group">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-cyan-500 text-white shadow-lg shadow-cyan-500/30 transition-transform group-hover:scale-110">
                            <span class="text-xs font-extrabold tracking-tighter">DN</span>
                        </div>
                        <span
                            class="text-xl font-bold tracking-tight text-white transition-colors group-hover:text-cyan-300">DuitNotes</span>
                    </Link>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        Asisten pencatat keuangan pribadi via WhatsApp. Pantau pengeluaran Anda cukup dengan modal chat.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Produk</h3>
                    <ul class="space-y-3">
                        <li><a href="#features"
                                class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">Fitur</a></li>
                        <li><a href="#pricing"
                                class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">Harga</a></li>
                        <li><a href="#faq" class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">Tanya
                                Jawab</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Legal</h3>
                    <ul class="space-y-3">
                        <li>
                            <Link href="#" class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">Syarat &
                                Ketentuan</Link>
                        </li>
                        <li>
                            <Link href="#" class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">
                                Kebijakan Privasi</Link>
                        </li>
                        <li>
                            <Link href="#" class="text-slate-400 hover:text-cyan-400 transition-colors text-sm">Hubungi
                                Kami</Link>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom line -->
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm">
                    &copy; {{ new Date().getFullYear() }} DuitNotes. All rights reserved.
                </p>

                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-500 hover:text-cyan-400 transition-colors">
                        <Twitter class="h-5 w-5" />
                    </a>
                    <a href="#" class="text-slate-500 hover:text-cyan-400 transition-colors">
                        <Instagram class="h-5 w-5" />
                    </a>
                    <a href="#" class="text-slate-500 hover:text-cyan-400 transition-colors">
                        <Github class="h-5 w-5" />
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
