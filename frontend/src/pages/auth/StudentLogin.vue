<template>
    <div class="split">
        <!-- LEFT: FORM -->
        <div class="form-side">
            <h2>{{ isRegister ? 'Student Register' : 'Student Login' }}</h2>

            <!-- LOGIN -->
            <div v-if="!isRegister">
                <input v-model="email" placeholder="Email" />
                <input
                    v-model="password"
                    type="password"
                    placeholder="Password"
                />

                <button @click="login">Login</button>
            </div>

            <!-- REGISTER -->
            <div v-else>
                <input v-model="form.student_id" placeholder="Student ID" />
                <input v-model="form.first_name" placeholder="First Name" />
                <input v-model="form.last_name" placeholder="Last Name" />

                <select v-model="form.degprog_id">
                    <option disabled value="">Select Degree Program</option>
                    <option
                        v-for="program in degreePrograms"
                        :key="program.degprog_id"
                        :value="program.degprog_id"
                    >
                        {{ program.degprog_name }}
                    </option>
                </select>

                <input v-model="form.up_email" placeholder="UP Email" />
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Password"
                />

                <button @click="register">Register</button>
            </div>

            <!-- TOGGLE -->
            <!-- TOGGLE BUTTONS -->
            <div class="auth-actions">
                <button
                    v-if="!isRegister"
                    class="secondary"
                    @click="toggleAuth"
                >
                    Don't have an account? Register here
                </button>

                <button v-else class="secondary" @click="toggleAuth">
                    ← Back to Login
                </button>
            </div>
        </div>

        <!-- RIGHT: IMAGE -->
        <div class="image-side"></div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { get, post } from '@/services/apiService';

const router = useRouter();
const auth = useAuthStore();

const isRegister = ref(false);

// LOGIN
const email = ref('');
const password = ref('');

// REGISTER
const degreePrograms = ref([]);

const form = reactive({
    student_id: '',
    first_name: '',
    last_name: '',
    up_email: '',
    degprog_id: '',
    password: '',
});

// FETCH DEGREE PROGRAMS
const fetchDegreePrograms = async () => {
    try {
        const res = await get('/degree-programs');
        degreePrograms.value = res.data ?? res;
    } catch (err) {
        console.error('Failed to load programs:', err);
    }
};

// TOGGLE
const toggleAuth = () => {
    isRegister.value = !isRegister.value;
};

// LOGIN
const login = async () => {
    try {
        const res = await post('/student-login', {
            email: email.value,
            password: password.value,
        });

        if (res.status === 'success') {
            auth.loginStudent(res.data, res.token || 'student-token');

            router.push('/dashboard');
        } else {
            alert(res.message || 'Login failed!');
        }
    } catch (err) {
        console.error(err);
        alert('Server error');
    }
};

// REGISTER
const register = async () => {
    try {
        const response = await post('/register-student', form);

        console.log('REGISTER RESPONSE:', response); // 🔥 ADD THIS

        if (response.status === 'success') {
            alert('Registration Successful!');
            router.push('/student-login');
        } else {
            console.log('VALIDATION ERRORS:', response.errors);
            alert(response.message || 'Registration failed');
        }
    } catch (err) {
        console.error('Network error:', err);
        alert('Could not connect to the server.');
    }
};

onMounted(fetchDegreePrograms);
</script>

<style scoped>
.auth-actions {
    margin-top: 15px;
    display: flex;
    justify-content: center;
}

/* secondary button (for toggle) */
.secondary {
    background: transparent;
    border: 2px solid #064e3b;
    color: #064e3b;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}

.secondary:hover {
    background: #064e3b;
    color: white;
}

/* REGISTER */
.register-link {
    margin-top: 12px;
    font-size: 14px;
    color: #555;
}

.register-link span {
    color: #064e3b;
    font-weight: 600;
    cursor: pointer;
}

.register-link span:hover {
    text-decoration: underline;
}

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
    width: 100%;
    padding: 12px;
    background: #7f1d1d; /* maroon */
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: rgb(3, 74, 46);
    color: gold;
    border: 2px solid gold;
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
