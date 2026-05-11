<template>
    <div class="modal-overlay">
        <div class="modal-card">
            <button class="close-btn" @click="$emit('close')">✕</button>

            <!-- HEADER -->
            <div class="modal-header">
                <h2 class="title">Application Form</h2>
                <p class="subtext">
                    Applying to: <b>{{ org?.org_name }}</b>
                </p>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="group">
                    <label>Cover Letter</label>
                    <textarea v-model="cover_letter" />
                </div>

                <div class="group">
                    <label>Skills</label>
                    <textarea v-model="skills" />
                </div>

                <div class="group">
                    <label>Previous Experience</label>
                    <textarea v-model="previous_experience" />
                </div>

                <div class="group">
                    <label>Committee</label>
                    <input
                        v-model="applied_committee"
                        placeholder="e.g. Logistics"
                    />
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="secondary" @click="$emit('close')">
                    Cancel
                </button>

                <button class="primary" @click="submit">
                    Submit Application
                </button>
            </div>
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
/* OVERLAY */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 2000;
    overflow-y: auto;
}

/* CARD */
.modal-card {
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    animation: pop 0.2s ease;
}

/* HEADER */
.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    flex-shrink: 0;
}

.title {
    font-size: 18px;
    font-weight: 800;
    margin: 0;
    color: #1f2937;
}

.subtext {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* BODY (SCROLLABLE) */
.modal-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    overflow-y: auto;
}

/* FORM GROUP */
.group label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #9ca3af;
    display: block;
    margin-bottom: 6px;
}

textarea,
input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 13px;
    outline: none;
    transition: 0.2s;
}

textarea {
    min-height: 90px;
    resize: vertical;
}

textarea:focus,
input:focus {
    border-color: #7f1d1d;
    box-shadow: 0 0 0 2px rgba(127, 29, 29, 0.1);
}

/* FOOTER */
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-shrink: 0;
}

/* BUTTONS */
.primary {
    padding: 10px 16px;
    background: #7f1d1d;
    color: white;
    border: 2px solid #7f1d1d;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border-color: gold;
}

.secondary {
    padding: 10px 16px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    cursor: pointer;
}

.close-btn {
    position: absolute;
    top: 12px;
    right: 14px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #6b7280;
    transition: 0.2s;
}

.close-btn:hover {
    color: #7f1d1d;
    transform: scale(1.1);
}

/* ANIMATION */
@keyframes pop {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* MOBILE FIX */
@media (max-width: 640px) {
    .modal-card {
        max-width: 100%;
        border-radius: 12px;
    }
}
</style>
