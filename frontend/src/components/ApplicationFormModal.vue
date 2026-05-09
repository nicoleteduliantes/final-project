<template>
    <div class="card">
        <h2>Application Form</h2>
        <p class="subtext">
            Applying to: <b>{{ org?.org_name }}</b>
        </p>

        <div class="form">
            <div class="group">
                <label>Cover Letter</label>
                <textarea v-model="cover_letter"></textarea>
            </div>

            <div class="group">
                <label>Skills</label>
                <textarea v-model="skills"></textarea>
            </div>

            <div class="group">
                <label>Previous Experience</label>
                <textarea v-model="previous_experience"></textarea>
            </div>

            <div class="group">
                <label>Committee</label>
                <input
                    v-model="applied_committee"
                    placeholder="e.g. Logistics"
                />
            </div>

            <button class="primary" @click="submit">Submit Application</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { post } from '@/services/apiService';

const props = defineProps({
    org: Object,
});

const emit = defineEmits(['submitted', 'close']);

const cover_letter = ref('');
const skills = ref('');
const previous_experience = ref('');
const applied_committee = ref('');

const submit = async () => {
    try {
        await post('/applications', {
            org_id: props.org.org_id,
            cover_letter: cover_letter.value,
            skills: skills.value,
            previous_experience: previous_experience.value,
            applied_committee: applied_committee.value,
            date_applied: new Date().toISOString().split('T')[0],
        });

        emit('submitted');
        emit('close');
    } catch (err) {
        console.error(err);
        alert(err.response?.data?.message || 'Failed to submit');
    }
};
</script>

<style scoped>
.card {
    width: 100%;
    background: white;
    padding: 24px;
    border-radius: 14px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

textarea,
input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.primary {
    margin-top: 10px;
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: 3px solid #7f1d1d;
    border-radius: 8px;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border: 3px solid gold;
}
</style>
