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

        <!-- POSTER GRID -->
        <div v-else class="poster-grid">
            <div class="poster" v-for="event in events" :key="event.event_id">
                <!-- IMAGE -->
                <div class="poster-image">
                    <img
                        :src="
                            event.image_url ||
                            'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1200&q=80'
                        "
                        alt="event poster"
                    />
                </div>

                <!-- CONTENT -->
                <div class="poster-content">
                    <div class="poster-top">
                        <div class="top-left">
                            <span class="tag">EVENT</span>
                        </div>

                        <span class="date">{{ event.date }}</span>
                    </div>

                    <h3 class="title">
                        {{ event.event_name }}
                    </h3>

                    <p class="location">📍 {{ event.location }}</p>
                    <p class="desc">
                        {{ event.description }}
                    </p>

                    <!-- ACTIONS -->
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
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import { get, del } from '@/services/apiService';

const router = useRouter();
const events = ref([]);

/* FETCH */
const fetchEvents = async () => {
    try {
        const res = await get('/org/events');
        events.value = res;
    } catch (error) {
        console.error('Error fetching events:', error);
    }
};

/* EDIT */
const edit = (id) => {
    router.push(`/org/events/edit/${id}`);
};

/* DELETE */
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
.top-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ORG BADGE */
.org {
    font-size: 11px;
    font-weight: 700;

    color: #14532d;
    background: rgba(20, 83, 45, 0.12);

    border: 1px solid rgba(20, 83, 45, 0.3);
    padding: 3px 10px;
    border-radius: 999px;

    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* HEADER */
.header {
    margin-bottom: 20px;
    padding-top: 20px;
}

h2 {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #7f1d1d;
}

.subtext {
    margin-top: 4px;
    font-size: 14px;
    color: #6b7280;
}

/* EMPTY */
.empty {
    padding: 20px;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
    text-align: center;
    color: #6b7280;
}

/* GRID */
.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

/* POSTER CARD */
.poster {
    background: white;
    border-radius: 16px;
    overflow: hidden;

    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);

    transition: 0.25s ease;
}

.poster:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
}

/* IMAGE */
.poster-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.poster-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* CONTENT */
.poster-content {
    padding: 16px;
}

/* TOP */
.poster-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.tag {
    background: #7f1d1d;
    color: white;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.date {
    font-size: 12px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    color: #374151;
}

/* TITLE */
.title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}

/* LOCATION */
.location {
    margin: 6px 0;
    font-size: 13px;
    color: #4b5563;
}

/* DESCRIPTION */
.desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;
    margin-top: 10px;
}

/* ACTIONS */
.actions {
    display: flex;
    gap: 10px;
    margin-top: 14px;
}

/* BUTTONS */
.btn {
    flex: 1;
    padding: 8px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
    border: none;
}

/* MAROON */
.edit {
    background: #7f1d1d;
    color: white;
}

.edit:hover {
    background: #5f1414;
}

/* OUTLINE RED */
.delete {
    background: transparent;
    border: 1px solid #7f1d1d;
    color: #7f1d1d;
}

.delete:hover {
    background: #7f1d1d;
    color: white;
}
</style>
