<template>
    <div class="auth">
        <h2>Student Register</h2>

        <input placeholder="Student ID" v-model="form.student_id" />

        <input placeholder="First Name" v-model="form.first_name" />

        <input placeholder="Last Name" v-model="form.last_name" />

        <input type="date" v-model="form.admission_date" />

        <select v-model="form.course_id">
            <option disabled value="">Select Degree Program</option>
            <option value="1">BS Computer Science</option>
            <option value="2">BS Information Systems</option>
            <option value="3">BS Mathematics</option>
        </select>

        <input type="email" placeholder="UP Email" v-model="form.up_email" />

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
    // no API yet
    console.log(form);

    router.push('/student-login');
};
</script>
