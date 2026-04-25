<template>
    <div class="auth">
        <h2>Admin Login</h2>

        <select v-model="type">
            <option value="org">Organization Admin</option>
            <option value="osa">OSA Admin</option>
        </select>

        <input placeholder="Email" v-model="email" />
        <input type="password" placeholder="Password" v-model="password" />

        <button @click="login">Login</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const email = ref('');
const password = ref('');
const type = ref('org');

const router = useRouter();
const auth = useAuthStore();

const login = () => {
    if (type.value === 'org') {
        auth.loginOrg({ email: email.value });
        router.push('/org/dashboard');
    } else {
        auth.loginOsa({ email: email.value });
        router.push('/osa/dashboard');
    }
};
</script>
