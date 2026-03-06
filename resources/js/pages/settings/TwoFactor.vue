<script setup lang="ts">
import axios from 'axios';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { computed, onUnmounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useAuth } from '@/composables/useAuth';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const { user, initialized, fetchUser } = useAuth();
const twoFactorEnabled = computed(() => !!user.value?.two_factor_confirmed_at);
const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref(false);
const processing = ref(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});

async function enableTwoFactor() {
    processing.value = true;
    try {
        await axios.post('/user/two-factor-authentication');
        initialized.value = false;
        await fetchUser();
        showSetupModal.value = true;
    } finally {
        processing.value = false;
    }
}

async function disableTwoFactor() {
    processing.value = true;
    try {
        await axios.delete('/user/two-factor-authentication');
        initialized.value = false;
        await fetchUser();
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <SettingsLayout>
        <h1 class="sr-only">Two-factor authentication settings</h1>

        <div class="space-y-6">
            <Heading
                variant="small"
                title="Two-factor authentication"
                description="Manage your two-factor authentication settings"
            />

            <div
                v-if="!twoFactorEnabled"
                class="flex flex-col items-start justify-start space-y-4"
            >
                <Badge variant="destructive">Disabled</Badge>

                <p class="text-muted-foreground">
                    When you enable two-factor authentication, you will be
                    prompted for a secure pin during login. This pin can be
                    retrieved from a TOTP-supported application on your phone.
                </p>

                <div>
                    <Button v-if="hasSetupData" @click="showSetupModal = true">
                        <ShieldCheck />Continue setup
                    </Button>
                    <Button v-else :disabled="processing" @click="enableTwoFactor">
                        <ShieldCheck />Enable 2FA
                    </Button>
                </div>
            </div>

            <div
                v-else
                class="flex flex-col items-start justify-start space-y-4"
            >
                <Badge variant="default">Enabled</Badge>

                <p class="text-muted-foreground">
                    With two-factor authentication enabled, you will be
                    prompted for a secure, random pin during login, which
                    you can retrieve from the TOTP-supported application on
                    your phone.
                </p>

                <TwoFactorRecoveryCodes />

                <Button variant="destructive" :disabled="processing" @click="disableTwoFactor">
                    <ShieldBan />Disable 2FA
                </Button>
            </div>

            <TwoFactorSetupModal
                v-model:isOpen="showSetupModal"
                :requiresConfirmation="false"
                :twoFactorEnabled="twoFactorEnabled"
            />
        </div>
    </SettingsLayout>
</template>
