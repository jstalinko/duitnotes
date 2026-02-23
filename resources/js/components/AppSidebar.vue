<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, ArrowLeftRight, Tag, FileText, Settings } from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import dashboard from '@/routes/dashboard';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard.index(),
        icon: LayoutGrid,
    },
    {
        title: 'Duit Notes',
        href: dashboard.duitNotes(),
        icon: ArrowLeftRight,
    },
    {
        title: 'Kategori',
        href: '/dashboard/category',
        icon: Tag,
    },
    {
        title: 'Laporan',
        href: '',
        icon: FileText,
    },
    {
        title: 'Pengaturan',
        href: '',
        icon: Settings,
    }
];

const footerNavItems: NavItem[] = [
    {
        title: 'Bantuan?',
        href: 'https://javara.digita/whatsapp',
        icon: Folder,
    },
    {
        title: 'Tutorial',
        href: 'https://youtube.com/javaradigital',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard.index()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
