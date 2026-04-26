<template>
    <div class="page">
        <h2>Create New Event</h2>

        <div class="form">
            <label>Event Name</label>
            <input v-model="event.name" />

            <label>Event Logo</label>
            <input type="file" disabled title="Images disabled for now to simplify" />

            <label>Event Date</label>
            <input type="date" v-model="event.date" />

            <label>Location</label>
            <input v-model="event.location" />
            
            <label>Description</label>
            <textarea v-model="event.description"></textarea>

            <button class="primary" @click="createEvent">Create Event</button>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { post } from '@/services/apiService'; 

const event = reactive({
    name: '',
    date: '',
    location: '',
    description: '', 
});

const createEvent = async () => {
    const payload = {
        event_name: event.name,
        date: event.date,
        location: event.location,
        description: event.description
    };

    try {
        await post('/org/events', payload);
        alert('Event Created!');
    } catch (error) {
        console.error("Error details:", error.response?.data);
        alert('Creation failed. Check console.');
    }
};
</script>