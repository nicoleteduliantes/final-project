<template>
    <div class="split">
        <div class="form-side">
            <h2>Admin Login</h2>

            <select v-model="type">
                <option value="org">Organization Admin</option>
                <option value="osa">OSA Admin</option>
            </select>

            <input v-model="email" placeholder="Email" />
            <input v-model="password" type="password" placeholder="Password" />

            <button @click="login">Login</button>
        </div>

        <div class="image-side"></div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const email = ref('');
const password = ref('');
const type = ref('org');

const router = useRouter();
const auth = useAuthStore();

const login = () => {
    if (type.value === 'org') {
        auth.loginOrg({ email: email.value });
    } else {
        auth.loginOsa({ email: email.value });
    }

    router.push('/dashboard');
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
