<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const form = ref({ current_password: '', password: '', password_confirmation: '' });
const errors = ref<Record<string, string>>({});
const processing = ref(false);
const recentlySuccessful = ref(false);

async function updatePassword() {
    processing.value = true;
    errors.value = {};
    recentlySuccessful.value = false;
    try {
        await axios.put('/settings/password', form.value);
        recentlySuccessful.value = true;
        form.value = { current_password: '', password: '', password_confirmation: '' };
        setTimeout(() => { recentlySuccessful.value = false; }, 2000);
    } catch (e: any) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors ?? {};
        }
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <SettingsLayout>
        <h1 class="sr-only">Password settings</h1>

        <div class="space-y-6">
            <Heading
                variant="small"
                title="Update password"
                description="Ensure your account is using a long, random password to stay secure"
            />

            <form class="space-y-6" @submit.prevent="updatePassword">
                <div class="grid gap-2">
                    <Label for="current_password">Current password</Label>
                    <Input
                        id="current_password"
                        v-model="form.current_password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                        placeholder="Current password"
                    />
                    <InputError :message="errors.current_password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">New password</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="New password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing" data-test="update-password-button">Save password</Button>
                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </SettingsLayout>
</template>
