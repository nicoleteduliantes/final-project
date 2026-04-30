<template>
    <div class="page">
        <h2>Upcoming Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="event in events" :key="event.id">
                    <td>{{ event.event_name }}</td>
                    <td>{{ event.location }}</td>
                    <td>{{ event.date }}</td>
                    <td>
                        <button @click="edit(event.event_id)">Edit</button>

                        <button @click="handleDelete(event.event_id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import { get, del } from '@/services/apiService'; 

const router = useRouter();
const events = ref([]);

const fetchEvents = async () => {
    try {
      
        const res = await get('/org/events');
        events.value = res;
    } catch (error) {
        console.error("Error fetching events:", error);
    }
};

const edit = (id) => {
    router.push(`/org/events/edit/${id}`);
};

const handleDelete = async (id) => {
    
    if (confirm("Are you sure you want to delete this event?")) {
        try {
            await del(`/org/events/${id}`);
            alert('Event deleted successfully!');
            
            // Refresh the list so the deleted event disappears immediately
            await fetchEvents(); 
        } catch (error) {
            console.error("Delete failed:", error);
            alert('Failed to delete event.');
        }
    }
};

// Automatically run the list of events when the user lands on the "Upcoming Events" page
onMounted(fetchEvents);
</script>
