<template>
    <div class="page">
        <h2>Event Editor</h2>

         <div class="form">
            <label>Event Name</label>
            <input v-model="event.event_name" />

            <label>Event Logo</label>
            <input type="file" disabled title="Images disabled for now to simplify" />

            <label>Event Date</label>
            <input type="date" v-model="event.date" />

            <label>Location</label>
            <input v-model="event.location" />
            
            <label>Description</label>
            <textarea v-model="event.description"></textarea>

            <button class="save" @click="updateEvent">Save Changes</button>
        </div>
    </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { get, put } from '@/services/apiService'; 
const route = useRoute();
const router = useRouter();
const eventId = route.params.id;
console.log("Current Event ID:", eventId); 

const event = reactive({
    event_name: '', 
    date: '',
    location: '',
    description: '',
});

// 1. Load the event data when page opens
const loadEvent = async () => {
    try {
        const data = await get(`/org/events/${eventId}`);
        event.event_name = data.event_name;
        event.date = data.date;
        event.location = data.location;
        event.description = data.description;
    } catch (error) {
        console.error("Failed to load event:", error);
        alert("Could not find this event.");
    }
};

// 2. Save the changes
const updateEvent = async () => {
    try {
        await put(`/org/events/${eventId}`, event);
        alert('Event updated successfully!');
        router.push('/org/events'); 
    } catch (error) {
        console.error("Update failed:", error);
        alert('Failed to update event.');
    }
};

onMounted(loadEvent);
</script>


<style scoped>
.page {
    padding: 20px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 400px;
}

input,
textarea {
    padding: 8px;
    border: 1px solid #ccc;
}

.save {
    background: #2563eb;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}
</style>
