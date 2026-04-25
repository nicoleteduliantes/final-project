<template>
    <div class="auth">
        <h2>Student Login</h2>

        <input placeholder="UP Email" v-model="email" />

        <button @click="login">Login</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const email = ref('');
const router = useRouter();
const auth = useAuthStore();

const login = () => {
    auth.loginStudent({
        email: email.value,
    });

    router.push('/dashboard');
};
</script>
