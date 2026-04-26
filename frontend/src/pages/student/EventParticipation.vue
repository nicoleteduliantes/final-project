<template>
    <div class="page">
        <h2>Event Participation</h2>

        <div class="card">
            <label>Event</label>
            <input v-model="eventName" disabled />

            <label>Date</label>
            <input v-model="date" disabled />

            <label>Hosts</label>
            <input v-model="hosts" disabled />

            <label>Role</label>
            <select v-model="role">
                <option>Participant</option>
                <option>Volunteer</option>
            </select>

            <button @click="submit">Join Event</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const eventName = ref('Hackathon');
const date = ref('May 20');
const hosts = ref('AWS Club');

const role = ref('Participant');

const submit = () => {
    console.log('joined event');
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
select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
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
    font-size: 14px;
}
</style>
