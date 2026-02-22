<script setup>
import { Link } from '@inertiajs/vue3';
import { Home, Star, CreditCard, Info, Mail, MessageSquareText } from 'lucide-vue-next';

const navItems = [
    { label: 'Home', href: '#home', icon: Home },
    { label: 'Feature', href: '#features', icon: Star },
    { label: 'Pricing', href: '#pricing', icon: CreditCard },
    { label: 'FAQ', href: '#faq', icon: MessageSquareText },
    { label: 'Contact', href: 'https://javara.digital/whatsapp', icon: Mail },
];
</script>

<template>
    <div class="fixed top-4 left-0 right-0 z-50 flex w-full justify-center px-4">
        <nav
            class="flex items-center justify-between gap-2 rounded-full border border-white/20 bg-gradient-to-r from-cyan-600/90 via-blue-600/90 to-purple-600/90 p-2 shadow-[0_8px_32px_rgba(234,88,12,0.3)] backdrop-blur-xl transition-all duration-300">
            <!-- Brand Logo -->
            <Link href="/" class="group flex items-center gap-2 pl-4 pr-2 sm:pr-6 text-white">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-orange-600 shadow-inner">
                    <span class="text-sm font-extrabold tracking-tighter">DN</span>
                </div>
                <span
                    class="hidden text-xl font-bold tracking-tight text-white lg:block transition-transform duration-300 group-hover:scale-105">DuitNotes</span>
            </Link>

            <!-- Nav Items -->
            <div class="flex items-center gap-1 sm:gap-2">
                <Link v-for="item in navItems" :key="item.label" :href="item.href"
                    class="group relative flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-white/80 transition-all duration-300 ease-out hover:bg-white/20 hover:text-white sm:px-4">
                    <component :is="item.icon"
                        class="h-4.5 w-4.5 transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110"
                        stroke-width="2.5" />
                    <span class="hidden md:block">{{ item.label }}</span>
                </Link>
            </div>

            <!-- Login / CTA -->
            <div class="flex items-center gap-2 pl-2 pr-1 sm:pl-4">
                <Link href="/login"
                    class="rounded-full bg-white px-5 py-2.5 text-sm font-bold text-blue-600 shadow-md transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-orange-900/20 active:scale-95">
                    Masuk
                </Link>
            </div>
        </nav>
    </div>
</template>