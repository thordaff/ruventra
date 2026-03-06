import axios from 'axios';
import { shallowRef, ref } from 'vue';
import {
    LayoutGrid,
    BarChart2,
    DollarSign,
    Users,
    Shield,
    Settings,
    FolderGit2,
    BookOpen,
} from 'lucide-vue-next';
import type { Component } from 'vue';
import type { NavItem } from '@/types';

const iconMap: Record<string, Component> = {
    LayoutGrid,
    BarChart2,
    DollarSign,
    Users,
    Shield,
    Settings,
    FolderGit2,
    BookOpen,
};

const mainNavItems = shallowRef<NavItem[]>([]);
const footerNavItems = shallowRef<NavItem[]>([]);
const loaded = ref(false);

function mapItem(item: any): NavItem {
    return {
        title: item.title,
        href: item.href,
        icon: item.icon ? (iconMap[item.icon] as any) : undefined,
        isActive: false,
        children: item.children?.map(mapItem),
    };
}

export function useNavItems() {
    async function fetchNavItems() {
        if (loaded.value) return;
        try {
            const res = await axios.get('/api/nav-items');
            mainNavItems.value = res.data.main.map(mapItem);
            footerNavItems.value = res.data.footer.map(mapItem);
            loaded.value = true;
        } catch {
            mainNavItems.value = [];
            footerNavItems.value = [];
        }
    }

    function resetNavItems() {
        loaded.value = false;
        mainNavItems.value = [];
        footerNavItems.value = [];
    }

    return { mainNavItems, footerNavItems, fetchNavItems, resetNavItems };
}
