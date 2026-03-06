<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useAuth } from '@/composables/useAuth';

const router = useRouter();
const { logout } = useAuth();
const passwordInput = ref<HTMLInputElement | null>(null);
const password = ref('');
const errors = ref<Record<string, string>>({});
const processing = ref(false);
const open = ref(false);

function resetForm() {
    password.value = '';
    errors.value = {};
}

async function deleteAccount() {
    processing.value = true;
    errors.value = {};
    try {
        await axios.delete('/settings/profile', { data: { password: password.value } });
        await logout();
        router.push('/');
    } catch (e: any) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors ?? {};
            passwordInput.value?.focus();
        }
    } finally {
        processing.value = false;
    }
}
</script>

<template>
    <div class="space-y-6">
        <Heading
            variant="small"
            title="Delete account"
            description="Delete your account and all of its resources"
        />
        <div
            class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
        >
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">Warning</p>
                <p class="text-sm">Please proceed with caution, this cannot be undone.</p>
            </div>
            <Dialog v-model:open="open">
                <DialogTrigger as-child>
                    <Button variant="destructive" data-test="delete-user-button">Delete account</Button>
                </DialogTrigger>
                <DialogContent>
                    <form class="space-y-6" @submit.prevent="deleteAccount">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                            <DialogDescription>
                                Once your account is deleted, all of its resources and data will also be
                                permanently deleted. Please enter your password to confirm you would like
                                to permanently delete your account.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">Password</Label>
                            <Input
                                id="password"
                                ref="passwordInput"
                                v-model="password"
                                type="password"
                                placeholder="Password"
                                autocomplete="current-password"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" type="button" @click="resetForm">Cancel</Button>
                            </DialogClose>
                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                Delete account
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
