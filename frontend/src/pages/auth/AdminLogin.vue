<template>
    <div class="auth">
        <h2>Admin Login</h2>

        <select v-model="type">
            <option value="org">Organization Admin</option>
            <option value="osa">OSA Admin</option>
        </select>

        <input v-model="email" placeholder="Email" />

        <button @click="login">Login</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const email = ref('');
const type = ref('org');

const router = useRouter();
const auth = useAuthStore();

const login = () => {
    auth.setUser({ email: email.value }, type.value);

    if (type.value === 'org') {
        router.push('/org/dashboard');
    } else {
        router.push('/osa/dashboard');
    }
};
</script>
