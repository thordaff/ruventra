<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import { useAuth } from '@/composables/useAuth';

const { hasRole } = useAuth();
</script>

<template>
  <SidebarProvider>
    <AppSidebar v-if="hasRole('developer') || hasRole('superAdmin')" />
    <SidebarInset>
      <main class="flex flex-col p-8">
        <slot />
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>
