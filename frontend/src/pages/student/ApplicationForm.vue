<template>
    <div class="page">
        <div class="card">
            <h2>Application Form</h2>
            <p class="subtext">
                Fill out your application to join the organization
            </p>

            <div class="form">
                <div class="group">
                    <label>Cover Letter</label>
                    <textarea
                        v-model="cover_letter"
                        placeholder="Tell us why you want to join..."
                    ></textarea>
                </div>

                <div class="group">
                    <label>Skills</label>
                    <textarea
                        v-model="skills"
                        placeholder="List your skills..."
                    ></textarea>
                </div>

                <div class="group">
                    <label>Previous Experience</label>
                    <textarea
                        v-model="previous_experience"
                        placeholder="Your experience..."
                    ></textarea>
                </div>

                <div class="group">
                    <label>Committee</label>
                    <input
                        v-model="applied_committee"
                        placeholder="e.g. Logistics, Marketing, Technical"
                    />
                </div>

                <button class="primary" @click="submit">
                    Submit Application
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { post } from '@/services/apiService';

const router = useRouter();
const route = useRoute();

/* FORM */
const cover_letter = ref('');
const skills = ref('');
const previous_experience = ref('');
const applied_committee = ref('');

const submit = async () => {
    try {
        const payload = {
            org_id: route.params.org_id,
            cover_letter: cover_letter.value,
            skills: skills.value,
            previous_experience: previous_experience.value,
            applied_committee: applied_committee.value,
            date_applied: new Date().toISOString().split('T')[0],
        };

        await post('/applications', payload);

        alert('Application submitted! Your membership is now pending.');
        router.push('/discover');
    } catch (err) {
        console.error(err);
        alert('Failed to submit application.');
    }
};
</script>

<style scoped>
.page {
    width: 100%;
    padding: 50px;

    display: flex;
    flex-direction: column;
    align-items: flex-start;

    text-align: left;
}

/* MATCHED REGISTER ORG CARD STYLE */
.card {
    width: 100%;
    max-width: 650px;
    background: white;
    padding: 28px;
    border-radius: 14px;
    border-left: 5px solid #7f1d1d;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

/* TEXT */
h2 {
    margin-bottom: 4px;
}

.subtext {
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 20px;
}

/* FORM */
.form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* GROUP */
.group {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

/* INPUTS */
input,
textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: 0.2s;
    font-family: inherit;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #7f1d1d;
}

/* TEXTAREA */
textarea {
    min-height: 100px;
    resize: vertical;
}

/* BUTTON (MATCH REGISTER ORG EXACTLY) */
.primary {
    margin-top: 8px;
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border: gold 2px solid;
}
</style>
