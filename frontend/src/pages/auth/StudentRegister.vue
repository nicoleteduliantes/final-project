<template>
    <div class="split">
        <div class="form-side">
            <h2>Student Register</h2>

            <input v-model="form.student_id" placeholder="Student ID" />
            <input v-model="form.first_name" placeholder="First Name" />
            <input v-model="form.last_name" placeholder="Last Name" />

            <input type="date" v-model="form.admission_date" />

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
            <input v-model="form.password" type="password" placeholder="Password" />

            <button @click="register">Register</button>
        </div>

        <div class="image-side"></div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { get } from '@/services/apiService';

const router = useRouter();

// Matches the backend data structure
const degreePrograms = ref([]);

const form = reactive({
    student_id: '',
    first_name: '',
    last_name: '',
    up_email: '',
    admission_date: '',
    degprog_id: '',
    password: '', 
});

// Fetches the programs we seeded from the StudentController index route
const fetchDegreePrograms = async () => {
    try {
        degreePrograms.value = await get('/degree-programs');
    } catch (err) {
        console.error("Failed to load programs:", err);
    }
};

onMounted(() => {
    fetchDegreePrograms();
});

const register = async () => {
    try {
        const response = await post('/register-student', JSON.stringify(form)
);

        const result = await response.json();

        if (response.ok) {
            alert('Registration Successful!');
            router.push('/student-login');
        } else {
            // This will alert validation errors (like email already taken)
            alert(result.message || 'Registration failed');
        }
    } catch (err) {
        console.error("Network error:", err);
        alert('Could not connect to the server.');
    }
};
</script>

<style scoped>
/* CSS remains exactly as you provided */
.split {
    display: flex;
    height: 100vh;
}

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

input,
select {
    padding: 12px;
    margin-bottom: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

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

.image-side {
    flex: 1;
    background-image: url('@/assets/upmin.jpg');
    background-size: cover;
    background-position: center;
}

@media (max-width: 768px) {
    .split {
        flex-direction: column;
    }

    .image-side {
        height: 200px;
    }
}
</style>