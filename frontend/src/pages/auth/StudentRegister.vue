<template>
    <div class="split">
        <!-- LEFT: FORM -->
        <div class="form-side">
            <h2>Student Register</h2>

            <input v-model="form.student_id" placeholder="Student ID" />
            <input v-model="form.first_name" placeholder="First Name" />
            <input v-model="form.last_name" placeholder="Last Name" />

            <input type="date" v-model="form.admission_date" />

            <select v-model="form.course_id">
                <option disabled value="">Select Degree Program</option>
                <option
                    v-for="course in courses"
                    :key="course.id"
                    :value="course.id"
                >
                    {{ course.name }}
                </option>
            </select>

            <input v-model="form.up_email" placeholder="UP Email" />

            <button @click="register">Register</button>
        </div>

        <!-- RIGHT: IMAGE -->
        <div class="image-side"></div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const courses = ref([]);

const form = reactive({
    student_id: '',
    first_name: '',
    last_name: '',
    up_email: '',
    admission_date: '',
    course_id: '',
});

const fetchCourses = async () => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/courses');
        courses.value = await res.json();
    } catch (err) {
        console.error(err);
    }
};

onMounted(() => {
    fetchCourses();
});

const register = () => {
    console.log('REGISTER DATA:', form);
    router.push('/student-login');
};
</script>

<style scoped>
/* SPLIT LAYOUT */
.split {
    display: flex;
    height: 100vh;
}

/* LEFT FORM */
.form-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px;
    background: #ffffff;
}

/* TITLE */
h2 {
    margin-bottom: 20px;
    color: #064e3b;
}

/* INPUTS */
input,
select {
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

/* RIGHT IMAGE */
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
