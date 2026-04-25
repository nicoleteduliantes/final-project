<template>
    <div class="auth">
        <h2>Student Login</h2>

        <input v-model="email" placeholder="UP Email" />

        <button @click="login">Login</button>

        <p v-if="error">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const email = ref('');
const error = ref('');
const router = useRouter();
const auth = useAuthStore();

const login = () => {
    if (!email.value) {
        error.value = 'Email is required';
        return;
    }

    auth.setUser({ email: email.value }, 'student');

    router.push('/dashboard');
};
</script>
