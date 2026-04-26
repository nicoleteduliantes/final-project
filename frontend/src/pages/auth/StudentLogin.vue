<template>
    <div class="split">
        <!-- LEFT: FORM -->
        <div class="form-side">
            <h2>Student Login</h2>

            <input v-model="email" placeholder="Email" />
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
import { post } from '@/services/apiService';

// 1. We use simple refs, matching your Admin Login style
const email = ref('');
const password = ref('');

const router = useRouter();
const auth = useAuthStore();

const login = async () => {
    try {
        // 2. We send an object containing the ref values, matching the backend keys
        const res = await post('/student-login', {
            email: email.value,
            password: password.value
        });

        // 3. Check for the data/status, similar to how Admin checks for res.token
        if (res.status === 'success') {
            // Use the loginStudent function from your auth store
            // We pass res.data (the student object) and res.token
            auth.loginStudent(res.data, res.token || 'student-token');
            
            router.push('/dashboard');
        } else {
            alert(res.message || 'Login failed! Check your credentials.');
        }
    } catch (err) {
        console.error("Network error:", err);
        alert('Could not connect to the server.');
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
