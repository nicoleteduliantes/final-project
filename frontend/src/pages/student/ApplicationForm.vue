<template>
    <div class="page">
        <h2>Application Form</h2>

        <div class="card">
            <label>Organization</label>
            <input :value="orgName" disabled />

            <label>Cover Letter</label>
            <textarea v-model="form.cover_letter"></textarea>

            <label>Skills</label>
            <textarea v-model="form.skills"></textarea>

            <label>Previous Experience</label>
            <textarea v-model="form.previous_experience"></textarea>

            <label>Committee</label>
            <input v-model="form.applied_committee" />

            <button @click="submit">Submit Application</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router'; // Added useRoute to get org_id
import { useAuthStore } from '@/stores/auth';
import { post } from '@/services/apiService';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

// Form Data Refs
const cover_letter = ref('');
const skills = ref('');
const previous_experience = ref('');
const applied_committee = ref('');

const submit = async () => {
    try {
        const payload = {
            // Pulls the ID from the URL (e.g., /apply/5)
            org_id: route.params.org_id,

            // Pulls the ID from your logged-in student's state
            student_id: authStore.user?.student_id,

            // Form inputs
            cover_letter: cover_letter.value,
            skills: skills.value,
            previous_experience: previous_experience.value,
            applied_committee: applied_committee.value,

            // Auto-generated date
            date_applied: new Date().toISOString().split('T')[0],
        };

        // Sends data to Laravel
        await post('/applications', payload);

        alert('Application submitted!');
        router.push('/discover');
    } catch (err) {
        console.error('Submission failed:', err);
        alert('Failed to submit application. Please try again.');
    }
};
</script>

<style scoped>
.page {
    padding: 20px;
}

.card {
    background: white;
    padding: 20px;
    width: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

input,
textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

textarea {
    min-height: 120px;
}

button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

label {
    font-weight: 600;
}
</style>
