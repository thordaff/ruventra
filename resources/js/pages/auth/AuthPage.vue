<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useAuth } from '@/composables/useAuth';

const router = useRouter();
const route = useRoute();
const { login, register } = useAuth();

const isLogin = ref(true);

function getRedirectTarget() {
    const redirect = route.query.redirect;
    return typeof redirect === 'string' && redirect.startsWith('/') ? redirect : '/dashboard';
}

// ── Login ────────────────────────────────────────────────────────────────────
const loginEmail = ref('');
const loginPassword = ref('');
const loginRemember = ref(false);
const loginErrors = ref<Record<string, string>>({});
const loginProcessing = ref(false);

async function handleLogin() {
    loginErrors.value = {};
    loginProcessing.value = true;
    try {
        await login(loginEmail.value, loginPassword.value, loginRemember.value);
        await router.push(getRedirectTarget());
    } catch (err: any) {
        if (err.response?.status === 422) {
            const data = err.response.data;
            loginErrors.value = data.errors ?? {};
            if (!Object.keys(loginErrors.value).length && data.message) {
                loginErrors.value = { email: data.message };
            }
        }
    } finally {
        loginProcessing.value = false;
    }
}

// ── Register ─────────────────────────────────────────────────────────────────
const registerName = ref('');
const registerEmail = ref('');
const registerPassword = ref('');
const registerPasswordConfirm = ref('');
const registerErrors = ref<Record<string, string>>({});
const registerProcessing = ref(false);

async function handleRegister() {
    registerErrors.value = {};
    registerProcessing.value = true;
    try {
        await register(registerName.value, registerEmail.value, registerPassword.value, registerPasswordConfirm.value);
        await router.push(getRedirectTarget());
    } catch (err: any) {
        if (err.response?.status === 422) {
            registerErrors.value = err.response.data.errors ?? {};
        }
    } finally {
        registerProcessing.value = false;
    }
}

function switchToRegister() {
    loginErrors.value = {};
    isLogin.value = false;
}

function switchToLogin() {
    registerErrors.value = {};
    isLogin.value = true;
}
</script>

<template>
    <div class="relative h-screen overflow-hidden bg-background">

        <!-- ─── Register form (left half) ──────────────────────────── -->
        <div
            class="absolute inset-y-0 left-0 flex w-full items-center justify-center px-8 transition-opacity duration-500 lg:w-1/2 lg:px-16"
            :class="isLogin ? 'pointer-events-none opacity-0' : 'opacity-100'"
        >
            <div class="w-full max-w-sm space-y-6">
                <!-- Mobile logo -->
                <div class="flex items-center gap-2 lg:hidden">
                    <AppLogoIcon class="size-7 text-foreground" />
                    <span class="text-lg font-semibold">Ruventra</span>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">Buat akun</h1>
                    <p class="text-sm text-muted-foreground">Masukkan data Anda untuk memulai</p>
                </div>

                <form class="space-y-4" @submit.prevent="handleRegister">
                    <div class="space-y-1.5">
                        <Label for="reg-name">Nama lengkap</Label>
                        <Input id="reg-name" v-model="registerName" type="text" placeholder="Nama Lengkap" autocomplete="name" required />
                        <InputError :message="registerErrors.name?.[0] ?? registerErrors.name" />
                    </div>
                    <div class="space-y-1.5">
                        <Label for="reg-email">Alamat email</Label>
                        <Input id="reg-email" v-model="registerEmail" type="email" placeholder="email@example.com" autocomplete="email" required />
                        <InputError :message="registerErrors.email?.[0] ?? registerErrors.email" />
                    </div>
                    <div class="space-y-1.5">
                        <Label for="reg-password">Password</Label>
                        <Input id="reg-password" v-model="registerPassword" type="password" placeholder="Min. 8 karakter" autocomplete="new-password" required />
                        <InputError :message="registerErrors.password?.[0] ?? registerErrors.password" />
                    </div>
                    <div class="space-y-1.5">
                        <Label for="reg-confirm">Konfirmasi kata sandi</Label>
                        <Input id="reg-confirm" v-model="registerPasswordConfirm" type="password" placeholder="Ulangi kata sandi" autocomplete="new-password" required />
                        <InputError :message="registerErrors.password_confirmation?.[0] ?? registerErrors.password_confirmation" />
                    </div>
                    <Button type="submit" class="mt-2 w-full" :disabled="registerProcessing">
                        {{ registerProcessing ? 'Membuat akun…' : 'Buat akun' }}
                    </Button>
                </form>

                <p class="text-center text-sm text-muted-foreground">
                    Sudah punya akun?
                    <button type="button" class="font-medium text-primary underline-offset-4 hover:underline" @click="switchToLogin">
                        Masuk
                    </button>
                </p>
            </div>
        </div>

        <!-- ─── Login form (right half) ───────────────────────────── -->
        <div
            class="absolute inset-y-0 right-0 flex w-full items-center justify-center px-8 transition-opacity duration-500 lg:w-1/2 lg:px-16"
            :class="isLogin ? 'opacity-100' : 'pointer-events-none opacity-0'"
        >
            <div class="w-full max-w-sm space-y-6">
                <!-- Mobile logo -->
                <div class="flex items-center gap-2 lg:hidden">
                    <AppLogoIcon class="size-7 text-foreground" />
                    <span class="text-lg font-semibold">Ruventra</span>
                </div>

                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight">Selamat datang kembali</h1>
                    <p class="text-sm text-muted-foreground">Masukkan kredensial Anda untuk mengakses akun</p>
                </div>

                <form class="space-y-4" @submit.prevent="handleLogin">
                    <div class="space-y-1.5">
                        <Label for="login-email">Alamat email</Label>
                        <Input id="login-email" v-model="loginEmail" type="email" placeholder="email@example.com" autocomplete="email" required />
                        <InputError :message="loginErrors.email?.[0] ?? loginErrors.email" />
                    </div>
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <Label for="login-password">Password</Label>
                            <a href="/forgot-password" class="text-sm text-muted-foreground underline-offset-4 hover:underline">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <Input id="login-password" v-model="loginPassword" type="password" placeholder="Password" autocomplete="current-password" required />
                        <InputError :message="loginErrors.password?.[0] ?? loginErrors.password" />
                    </div>
                    <div class="flex items-center gap-2">
                        <Checkbox id="login-remember" v-model:checked="loginRemember" />
                        <Label for="login-remember" class="cursor-pointer font-normal">Ingat saya</Label>
                    </div>
                    <Button type="submit" class="mt-2 w-full" :disabled="loginProcessing">
                        {{ loginProcessing ? 'Masuk…' : 'Masuk' }}
                    </Button>
                </form>

                <p class="text-center text-sm text-muted-foreground">
                    Belum punya akun?
                    <button type="button" class="font-medium text-primary underline-offset-4 hover:underline" @click="switchToRegister">
                        Daftar
                    </button>
                </p>
            </div>
        </div>

        <!-- ─── Illustration panel (slides left ↔ right) ────────────── -->
        <div
            class="absolute inset-y-0 left-0 hidden w-1/2 flex-col justify-between overflow-hidden bg-zinc-950 p-12 text-white transition-transform duration-700 ease-in-out lg:flex"
            :style="{ transform: isLogin ? 'translateX(0)' : 'translateX(100%)' }"
        >
            <!-- Dot grid background -->
            <div
                class="absolute inset-0 opacity-30"
                style="background-image: radial-gradient(circle, rgba(255,255,255,0.6) 1px, transparent 1px); background-size: 28px 28px;"
            />
            <!-- Glow orbs -->
            <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-indigo-600/20 blur-3xl" />
            <div class="absolute -bottom-24 -left-16 h-80 w-80 rounded-full bg-violet-600/20 blur-3xl" />

            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3">
                <AppLogoIcon class="size-9 text-white" />
                <span class="text-xl font-semibold tracking-tight">Ruventra</span>
            </div>

            <!-- Abstract graphic - geometric shapes -->
            <div class="relative z-10 flex flex-1 items-center justify-center py-8">
                <div class="relative flex items-center justify-center">
                    <!-- Outer ring -->
                    <div class="absolute h-72 w-72 rounded-full border border-white/10" />
                    <div class="absolute h-56 w-56 rounded-full border border-white/10" />
                    <div class="absolute h-40 w-40 rounded-full border border-white/15" />
                    <!-- Central hexagon-like shape via rotated squares -->
                    <div class="h-28 w-28 rotate-45 rounded-xl border border-indigo-400/40 bg-indigo-500/10" />
                    <div class="absolute h-20 w-20 rotate-12 rounded-xl border border-violet-400/40 bg-violet-500/10" />
                    <!-- dots on ring -->
                    <div class="absolute top-0 h-2.5 w-2.5 -translate-y-1/2 rounded-full bg-indigo-400" style="top: calc(50% - 7rem);" />
                    <div class="absolute h-2.5 w-2.5 rounded-full bg-violet-400" style="top: calc(50% + 7rem); left: 50%; transform: translate(-50%, 50%);" />
                    <div class="absolute h-2 w-2 rounded-full bg-white/60" style="left: calc(50% - 7rem); top: 50%; transform: translate(-50%, -50%);" />
                    <div class="absolute h-2 w-2 rounded-full bg-white/40" style="left: calc(50% + 7rem); top: 50%; transform: translate(50%, -50%);" />
                </div>
            </div>

            <!-- Quote -->
            <div class="relative z-10 space-y-3">
                <p class="text-base font-medium leading-relaxed text-white/90">
                    "Kelola acara Anda dengan mudah. Semua yang Anda butuhkan, tersedia dalam satu platform."
                </p>
                <p class="text-sm text-white/40">- Rumah Event Nusantara</p>
            </div>
        </div>
    </div>
</template>
