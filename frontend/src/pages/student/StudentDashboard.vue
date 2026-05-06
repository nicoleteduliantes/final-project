<template>
    <div>
        <h1>Dashboard</h1>

        <section>
            <h2>Announcements</h2>
            <div class="card">Admin announcement here</div>
        </section>

        <section>
            <h2>Upcoming Events</h2>

            <div class="event" v-for="event in events" :key="event.event_id">
                <h3>{{ event.event_name }}</h3>
                <p>{{ event.description }}</p>
                <RouterLink :to="'/event/' + event.event_id">
                    View Details
                </RouterLink>
            </div>
        </section>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import { get} from '@/services/apiService'; 

const events = ref([]);

const fetchEvents = async () => {
    try {
      
        const res = await get('/student/events');
        events.value = res;
    } catch (error) {
        console.error("Error fetching events:", error);
    } 
};
//to format date structure
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric'
    });
};

onMounted(fetchEvents);

</script>
