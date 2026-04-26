<template>
    <div class="split">
        <!-- LEFT: FORM -->
        <div class="form-side">
            <h2>Admin Login</h2>

            <select v-model="type">
                <option value="org">Organization Admin</option>
                <option value="osa">OSA Admin</option>
            </select>

            <input v-model="email" placeholder="Admin Id" />
            <input v-model="password" type="password" placeholder="Password" />

            <button @click="login">Login</button>
        </div>

        <!-- RIGHT: IMAGE -->
        <div class="image-side"></div>
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
        type: type.value,
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
        alert('Login failed! Check your credentials.');
    }
};
</script>

<style scoped>
/* SPLIT LAYOUT */
.split {
    display: flex;
    height: 100vh;
}

/* FORM SIDE */
.form-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px;
    background: #ffffff;
}

h2 {
    margin-bottom: 20px;
    color: #064e3b;
}

/* INPUTS */
input {
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

/* BUTTON */
button {
    padding: 12px;
    background: #7f1d1d; /* maroon */
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

/* IMAGE SIDE */
.image-side {
    flex: 1;
    background-image: url('@/assets/upmin.jpg');
    background-size: cover;
    background-position: center;
}

/* MOBILE */
@media (max-width: 768px) {
    .split {
        flex-direction: column;
    }

    .image-side {
        height: 200px;
    }
}
</style>
