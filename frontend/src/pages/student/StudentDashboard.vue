<template>
    <div>
        <h1>Dashboard</h1>

        <section>
            <h2>Announcements</h2>
            <div class="card">Admin announcement here</div>
        </section>

        <section>
            <h2>Upcoming Events</h2>

            <table class="event-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Host</th> <th>Location</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="event in events" :key="event.event_id">
                        <td class="event-name">{{ event.event_name }}</td>
                        <td>{{ event.organization?.org_name || 'School Event' }}</td>
                        <td>{{ event.location }}</td>
                        <td>{{ event.event_date }}</td>
                        <td>
                            <RouterLink :to="'/event/' + event.event_id" class="view-btn">
                                View Details
                            </RouterLink>
                        </td>
                    </tr>
                    <tr v-if="events.length === 0">
                        <td colspan="5" style="text-align: center; padding: 20px;">
                            No upcoming events found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import { get} from '@/services/apiService'; 

const events = ref([]);

const fetchEvents = async () => {
    try {
      
        const res = await get('/events');
        events.value = res;
    } catch (error) {
        console.error("Error fetching events:", error);
    } 
};

onMounted(fetchEvents);

</script>
