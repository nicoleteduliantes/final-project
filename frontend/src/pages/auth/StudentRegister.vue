<template>
    <div class="auth">
        <h2>Student Register</h2>

        <input v-model="form.student_id" placeholder="Student ID" />
        <input v-model="form.first_name" placeholder="First Name" />
        <input v-model="form.last_name" placeholder="Last Name" />
        <input type="date" v-model="form.admission_date" />

        <select v-model="form.course_id">
            <option disabled value="">Select Degree Program</option>
            <option value="1">BS Computer Science</option>
            <option value="2">BS Information Technology</option>
            <option value="3">BS Mathematics</option>
        </select>

        <input v-model="form.up_email" placeholder="UP Email" />

        <button @click="register">Register</button>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = reactive({
    student_id: '',
    first_name: '',
    last_name: '',
    up_email: '',
    admission_date: '',
    course_id: '',
});

const register = () => {
    console.log('REGISTER DATA:', form);

    router.push('/student-login');
};
</script>
