<script setup lang="ts">
import { LogOut, Settings } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { useAuth } from '@/composables/useAuth';
import { edit } from '@/routes/profile';
import type { User } from '@/types';

type Props = {
    user: User;
};

defineProps<Props>();

const router = useRouter();
const { logout } = useAuth();

const handleLogout = async () => {
    await logout();
    router.push('/');
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <RouterLink class="block w-full cursor-pointer" :to="edit().url">
                <Settings class="mr-2 h-4 w-4" />
                Settings
            </RouterLink>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem data-test="logout-button" @click="handleLogout">
        <LogOut class="mr-2 h-4 w-4" />
        Log out
    </DropdownMenuItem>
</template>
