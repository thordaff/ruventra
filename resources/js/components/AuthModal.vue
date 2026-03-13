<template>
    <!-- Modal Login/Register Flip Card -->
    <div
        v-if="show"
        class="fixed inset-0 z-9999 flex items-center justify-center bg-black/70"
        role="dialog"
        aria-modal="true"
        @click.self="emit('close')"
        @keydown.escape="emit('close')"
    >
        <div class="w-full max-w-md relative" style="perspective: 1000px">
            <div
                class="relative w-full"
                style="transform-style: preserve-3d; transition: transform 0.6s; min-height: 380px"
                :style="{ transform: showRegister ? 'rotateY(180deg)' : 'rotateY(0deg)' }"
            >
                <!-- Login Side -->
                <div
                    class="absolute w-full p-8 bg-white rounded-lg shadow flex flex-col items-center"
                    style="backface-visibility: hidden; min-height: 380px"
                >
                    <button
                        @click="emit('close')"
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl"
                    >
                        &times;
                    </button>
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Login</h2>
                    <form @submit.prevent="doLogin" class="w-full flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input
                                v-model="loginForm.email"
                                type="email"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input
                                v-model="loginForm.password"
                                type="password"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <p v-if="loginError" class="text-red-500 text-sm">{{ loginError }}</p>
                        <button
                            type="submit"
                            :disabled="loginLoading"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition disabled:opacity-50"
                        >
                            {{ loginLoading ? 'Loading...' : 'Login' }}
                        </button>
                    </form>
                    <div class="mt-4 w-full flex flex-col items-center gap-2">
                        <button
                            type="button"
                            @click="showRegister = true"
                            class="text-blue-600 hover:underline"
                        >
                            Belum punya akun? Register
                        </button>
                        <router-link
                            to="/reset-password"
                            class="text-sm text-gray-500 hover:text-blue-600 hover:underline"
                            @click="emit('close')"
                        >
                            Lupa password?
                        </router-link>
                    </div>
                </div>

                <!-- Register Side -->
                <div
                    class="absolute w-full p-8 bg-white rounded-lg shadow flex flex-col items-center"
                    style="
                        backface-visibility: hidden;
                        transform: rotateY(180deg);
                        min-height: 380px;
                    "
                >
                    <button
                        @click="emit('close')"
                        class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl"
                    >
                        &times;
                    </button>
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Register</h2>
                    <form @submit.prevent="doRegister" class="w-full flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input
                                v-model="registerForm.name"
                                type="text"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input
                                v-model="registerForm.email"
                                type="email"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input
                                v-model="registerForm.password"
                                type="password"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700"
                                >Confirm Password</label
                            >
                            <input
                                v-model="registerForm.password_confirmation"
                                type="password"
                                required
                                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <p v-if="registerError" class="text-red-500 text-sm">{{ registerError }}</p>
                        <button
                            type="submit"
                            :disabled="registerLoading"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded transition disabled:opacity-50"
                        >
                            {{ registerLoading ? 'Loading...' : 'Register' }}
                        </button>
                    </form>
                    <div class="mt-4 w-full flex flex-col items-center gap-2">
                        <button
                            type="button"
                            @click="showRegister = false"
                            class="text-blue-600 hover:underline"
                        >
                            Sudah punya akun? Login
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import { useToast } from '@/composables/useToast';

defineProps<{ show: boolean }>();
const emit = defineEmits(['close']);

const router = useRouter();
const { login, register } = useAuth();

const showRegister = ref(false);

const loginForm = reactive({ email: '', password: '' });
const loginError = ref('');
const loginLoading = ref(false);

const registerForm = reactive({ name: '', email: '', password: '', password_confirmation: '' });
const registerError = ref('');
const registerLoading = ref(false);

async function doLogin() {
    loginError.value = '';
    loginLoading.value = true;
    const { toast } = useToast();
    try {
        await login(loginForm.email, loginForm.password);
        toast('Selamat datang kembali! 👋', 'success');
        emit('close');
        router.push('/dashboard');
    } catch (err: any) {
        loginError.value =
            err.response?.data?.errors?.email?.[0] ??
            err.response?.data?.message ??
            'Login gagal.';
    } finally {
        loginLoading.value = false;
    }
}

async function doRegister() {
    registerError.value = '';
    registerLoading.value = true;
    try {
        await register(
            registerForm.name,
            registerForm.email,
            registerForm.password,
            registerForm.password_confirmation,
        );
        emit('close');
        router.push('/dashboard');
    } catch (err: any) {
        registerError.value =
            err.response?.data?.errors?.email?.[0] ??
            err.response?.data?.message ??
            'Register gagal.';
    } finally {
        registerLoading.value = false;
    }
}
</script>
