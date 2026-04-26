<template>
    <div class="page">
        <h2>Upcoming Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Participants</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="event in events" :key="event.id">
                    <td>{{ event.event_name }}</td>
                    <td>{{ event.date }}</td>
                    <td>{{ event.location }}</td>
                    <td>
                        <button @click="edit(event.event_id)">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { get } from '@/services/apiService'; // Imported your GET function

const router = useRouter();
const events = ref([]); // This will hold the events from the DB

const fetchEvents = async () => {
    try {
        // We use the 'get' function from your apiService
        const data = await get('/org/events');
        
        // If the backend returns an array, save it
        if (Array.isArray(data)) {
            events.value = data;
        } else {
            // Sometimes Laravel returns { data: [...] } depending on pagination
            events.value = data.data || [];
        }
    } catch (error) {
        console.error("Error fetching events:", error);
    }
};

const edit = (id) => {
    router.push(`/org/events/edit/${id}`);
};

// Automatically run when the user lands on the "Upcoming Events" page
onMounted(() => {
    fetchEvents();
});
</script>