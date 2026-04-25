<template>
    <div class="page">
        <h2>Upcoming Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Participants</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="event in events" :key="event.id">
                    <td>{{ event.name }}</td>
                    <td>{{ event.date }}</td>
                    <td>{{ event.count }}</td>
                    <td>
                        <button @click="edit(event.id)">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();

const edit = (id) => {
    router.push(`/org/events/edit/${id}`);
};

const events = [{ id: 1, name: 'Hackathon', date: 'March 10', count: 50 }];
</script>
