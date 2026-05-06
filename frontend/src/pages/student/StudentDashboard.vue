<template>
    <div class="page">
        <h1>Dashboard</h1>

        <!-- ANNOUNCEMENTS -->
        <section>
            <h2>Announcements</h2>

            <div class="card announcement-card">Admin announcement here</div>
        </section>

        <!-- UPCOMING EVENTS -->
        <section>
            <h2>Announcements</h2>
            <div class="card">Admin announcement here</div>
        </section>

        <section>
            <h2>Upcoming Events</h2>

            <div class="grid">
                <div class="card" v-for="event in events" :key="event.event_id">
                    <!-- BANNER -->
                    <div class="banner">
                        <img
                            :src="event.banner_url || fallbackBanner"
                            alt="event banner"
                        />
                    </div>

                    <!-- BODY -->
                    <div class="card-body">
                        <h3>{{ event.event_name }}</h3>

                        <p class="category">
                            {{ event.category || 'Event' }}
                        </p>

                        <p class="desc">
                            {{ event.description }}
                        </p>

                        <p class="desc">📅 {{ formatDate(event.date) }}</p>
                        <p class="category">
                            {{ event.org_name }}
                        </p>

                        <!-- ACTION -->
                        <div class="actions">
                            <RouterLink
                                :to="'/event/' + event.event_id"
                                class="btn link"
                            >
                                View Event
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>

import { ref, onMounted } from 'vue';
import { get} from '@/services/apiService'; 

const events = ref([]);

const fallbackBanner =
    'https://up.edu.ph/wp-content/uploads/2024/05/UP-Mindanao-by-Jonathan-Madrid-2048x1024.jpg';

const fetchEvents = async () => {
    try {
        const res = await get('/events');
        events.value = res.data ?? res;
    } catch (error) {
        console.error('Error fetching events:', error);
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};

onMounted(fetchEvents);

</script>

<style scoped>
/* GRID (same as org page) */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
}

/* CARD */
.card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: 0.2s ease;
}

.card:hover {
    transform: translateY(-3px);
}

/* BANNER */
.banner {
    height: 120px;
    overflow: hidden;
}

.banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* BODY */
.card-body {
    padding: 14px;
}

.category {
    color: #7f1d1d;
    font-weight: 600;
    font-size: 13px;
    margin: 5px 0;
}

.desc {
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
}

/* ACTIONS */
.actions {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

/* BUTTON BASE */
.btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;

    text-decoration: none;
    font-weight: 600;

    transition: 0.2s ease;
}

/* VIEW BUTTON */
.link {
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}

.link:hover {
    background: #7f1d1d;
    color: white;
}

/* ANNOUNCEMENT CARD */
.announcement-card {
    padding: 14px;
    font-size: 14px;
    color: #333;
}
</style>
