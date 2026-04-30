<template>
    <div class="page">
        <h2>Application Form</h2>

        <div class="card">
            <label>Cover Letter</label>
            <textarea v-model="cover_letter"></textarea>

            <label>Skills</label>
            <textarea v-model="skills"></textarea>

            <label>Previous Experience</label>
            <textarea v-model="previous_experience"></textarea>

            <label>Committee</label>
            <input v-model="applied_committee" />

            <button @click="submit">Submit Application</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { post } from '@/services/apiService';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

// Form Refs
const cover_letter = ref('');
const skills = ref('');
const previous_experience = ref('');
const applied_committee = ref('');

const submit = async () => {
    try {
        const payload = {
            // Needed to create the Membership row
            org_id: route.params.org_id,

            // Details for the application_details row
            cover_letter: cover_letter.value,
            skills: skills.value,
            previous_experience: previous_experience.value,
            applied_committee: applied_committee.value,
            date_applied: new Date().toISOString().split('T')[0],
        };

        // This call handles BOTH table insertions on the backend
        await post('/applications', payload);

        alert('Application submitted! Your membership is now pending.');
        router.push('/discover');
    } catch (err) {
        console.error('Submission failed:', err);
        alert('Failed to submit application.');
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
