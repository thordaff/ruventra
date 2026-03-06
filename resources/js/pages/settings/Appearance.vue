<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
</script>

<template>
    <SettingsLayout>
        <div class="space-y-6">
            <Heading
                variant="small"
                title="Appearance settings"
                description="Update your account's appearance settings"
            />
            <AppearanceTabs />
        </div>
    </SettingsLayout>
</template>
