<template>
    <div>
        <h1>Event Participation</h1>

        <input v-model="eventName" disabled />
        <input v-model="date" disabled />
        <input v-model="hosts" disabled />

        <select v-model="role">
            <option>Participant</option>
            <option>Volunteer</option>
        </select>

        <button @click="submit">Join</button>
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
