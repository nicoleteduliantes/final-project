<template>
    <div class="auth">
        <h2>Admin Login</h2>

        <select v-model="type">
            <option value="org">Organization Admin</option>
            <option value="osa">OSA Admin</option>
        </select>

        <input v-model="email" placeholder="Admin Id" />
        <input v-model="password" type="password" placeholder="Password" />
        
        <button @click="login">Login</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { post } from '@/services/apiService'; //bridge to Laravel

const email = ref('');
const type = ref('org');
const password = ref('');

const router = useRouter();
const auth = useAuthStore();

const login = async () => {
    const res = await post('/admin/login', {
        id: email.value, 
        password: password.value,
        type: type.value
    });

    if (res.token) {
        if (type.value === 'org') {
            auth.loginOrg(res.user, res.token); 
            router.push('/org/dashboard');
        } else {
            auth.loginOsa(res.user, res.token);
            router.push('/osa/dashboard');
        }
    } else {
        alert("Login failed! Check your credentials.");
    }
};

</script>
