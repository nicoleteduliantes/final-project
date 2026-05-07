<template>
    <div class="page">
        <div class="header">
            <h2>Upcoming Events</h2>
            <p class="subtext">Manage and monitor organization events</p>
        </div>

        <!-- EMPTY STATE -->
        <div v-if="events.length === 0" class="empty">
            No upcoming events found.
        </div>

        <!-- EVENTS GRID -->
        <div v-else class="grid">
            <div class="card" v-for="event in events" :key="event.event_id">
                <div class="card-top">
                    <h3 class="title">{{ event.event_name }}</h3>
                    <span class="date">{{ event.date }}</span>
                </div>

                <p class="location">📍 {{ event.location }}</p>

                <div class="actions">
                    <button class="btn edit" @click="edit(event.event_id)">
                        Edit
                    </button>

                    <button
                        class="btn delete"
                        @click="handleDelete(event.event_id)"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
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
        console.error('Error fetching events:', error);
    }
};

const edit = (id) => {
    router.push(`/org/events/edit/${id}`);
};

const handleDelete = async (id) => {
    if (confirm('Are you sure you want to delete this event?')) {
        try {
            await del(`/org/events/${id}`);
            await fetchEvents();
        } catch (error) {
            console.error('Delete failed:', error);
        }
    }
};

onMounted(fetchEvents);
</script>

<style scoped>
/* HEADER */
.header {
    padding-top: 20px;
    margin-bottom: 20px;
}

h2 {
    margin: 0;
}

.subtext {
    color: #6b7280;
    font-size: 14px;
    margin-top: 4px;
}

/* EMPTY STATE */
.empty {
    padding: 20px;
    background: white;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
    text-align: center;
    color: #6b7280;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
}

/* CARD */
.card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    border-left: 4px solid #7f1d1d;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    transition: 0.2s;
}

.card:hover {
    transform: translateY(-3px);
}

/* TOP */
.card-top {
    display: flex;
    justify-content: space-between;
    align-items: start;
}

.title {
    font-size: 16px;
    margin: 0;
    color: #111827;
}

.date {
    font-size: 12px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    color: #374151;
}

/* LOCATION */
.location {
    margin: 10px 0;
    font-size: 13px;
    color: #4b5563;
}

/* ACTIONS */
.actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

/* BUTTON BASE */
.btn {
    flex: 1;
    padding: 7px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
    transition: 0.2s;
}

/* EDIT AND DELETE */
.edit,
.delete {
    background: white;
    color: #7f1d1d;
    border: 1px solid #7f1d1d;
}

.edit:hover,
.delete:hover {
    background: #7f1d1d;
    color: white;
}
</style>
