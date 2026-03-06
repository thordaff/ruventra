<script setup lang="ts">
import axios from 'axios';
import { computed, ref, watch } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useAuth } from '@/composables/useAuth';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const { user, initialized, fetchUser } = useAuth();

const form = ref({ name: '', email: '' });
const errors = ref<Record<string, string>>({});
const processing = ref(false);
const recentlySuccessful = ref(false);
const verificationSent = ref(false);

const mustVerifyEmail = computed(() => !!user.value && !user.value.email_verified_at);

watch(() => user.value, (u) => {
    if (u) {
        form.value.name = u.name;
        form.value.email = u.email;
    }
}, { immediate: true });

async function updateProfile() {
    processing.value = true;
    errors.value = {};
    recentlySuccessful.value = false;
    try {
        await axios.patch('/settings/profile', form.value);
        recentlySuccessful.value = true;
        initialized.value = false;
        await fetchUser();
        setTimeout(() => { recentlySuccessful.value = false; }, 2000);
    } catch (e: any) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors ?? {};
        }
    } finally {
        processing.value = false;
    }
}

async function sendVerification() {
    await axios.post('/email/verification-notification');
    verificationSent.value = true;
}
</script>

<template>
    <SettingsLayout>
        <h1 class="sr-only">Profile settings</h1>

        <div class="flex flex-col space-y-6">
            <Heading
                variant="small"
                title="Profile information"
                description="Update your name and email address"
            />

            <form class="space-y-6" @submit.prevent="updateProfile">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        class="mt-1 block w-full"
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autocomplete="username"
                        placeholder="Email address"
                    />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div v-if="mustVerifyEmail">
                    <p class="-mt-4 text-sm text-muted-foreground">
                        Your email address is unverified.
                        <button
                            type="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            @click="sendVerification"
                        >
                            Click here to resend the verification email.
                        </button>
                    </p>
                    <div v-if="verificationSent" class="mt-2 text-sm font-medium text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing" data-test="update-profile-button">Save</Button>
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

        <DeleteUser />
    </SettingsLayout>
</template>
