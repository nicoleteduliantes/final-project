<template>
    <div class="page">
        <h2>Create New Event</h2>

        <div class="form">
            <label>Event Name</label>
            <input v-model="event.event_name" />

            <label>Event Date</label>
            <input type="date" v-model="event.date" />

            <label>Location</label>
            <input v-model="event.location" />

            <label>Description</label>
            <textarea v-model="event.description"></textarea>

            <button class="primary" @click = "createEvent">Create Event</button>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { post } from '@/services/apiService'; 

const event = reactive({
    event_name: '',
    date: '',
    location: '',
    description: '',
});

const createEvent = async () => {
    const payload = {
        event_name: event.event_name,
        date: event.date,
        location: event.location,
        description: event.description
    };

    try {
        await post('/org/events', payload);
        alert('Event Created!');

        event.event_name = '';
        event.date = '';
        event.location = '';
        event.description = '';
        
    } catch (error) {
        alert('Creation failed. Check console.');
    }
};
</script>
