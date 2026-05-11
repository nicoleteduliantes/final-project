<template>
    <div class="page">
        <h1 class="page-title">Dashboard</h1>

        <!-- ANNOUNCEMENTS -->
        <section class="section">
            <h2 class="section-title">Campus Announcements</h2>

            <div v-if="announcements.length === 0" class="empty">
                No announcements found.
            </div>

            <div v-else class="announcement-stack">
                <div
                    class="poster announcement-card"
                    v-for="ann in announcements"
                    :key="ann.announcement_id"
                >
                    <div class="poster-content">
                        <div class="poster-top">
                            <span class="tag announcement-tag"
                                >ANNOUNCEMENT</span
                            >
                            <span class="date">{{
                                formatDate(ann.date_posted)
                            }}</span>
                        </div>

                        <h3 class="title">{{ ann.title }}</h3>

                        <p class="posted-by">
                            Posted by:
                            <b>
                                {{
                                    ann.osa_id
                                        ? 'Office of the Student Affairs'
                                        : ann.organization?.org_name ||
                                          'Organization'
                                }}
                            </b>
                        </p>

                        <p class="desc">{{ ann.content }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">Upcoming Events</h2>

            <div v-if="events.length === 0" class="empty">
                No upcoming events found.
            </div>

            <div v-else class="poster-grid">
                <div
                    class="poster"
                    v-for="event in events"
                    :key="event.event_id"
                >
                    <div class="poster-image">
                        <img
                            :src="
                                event.image_url ||
                                'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1200&q=80'
                            "
                            alt="event poster"
                        />
                    </div>

                    <div class="poster-content">
                        <div class="poster-top">
                            <span class="tag">{{ event.event_host }}</span>
                            <span class="date">{{
                                formatDate(event.date)
                            }}</span>
                        </div>

                        <h3 class="title">{{ event.event_name }}</h3>

                        <p class="location">📍 {{ event.location }}</p>

                        <p class="desc">{{ event.description }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get } from '@/services/apiService';

const events = ref([]);
const announcements = ref([]);

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString();
};

const fetchData = async () => {
    try {
        const eventRes = await get('/events');
        events.value = eventRes.data ?? eventRes;

        const annRes = await get('/announcements');
        announcements.value = annRes.data ?? annRes;
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    }
};

onMounted(fetchData);
</script>

<style scoped>
.page {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* HEADER */
.header {
    margin-bottom: 20px;
    padding-top: 20px;
}

.subtext {
    margin-top: 4px;
    font-size: 14px;
    color: #6b7280;
}

/* SECTION */
.section {
    margin-bottom: 28px;
}

.section-title {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 12px;
}

/* EMPTY */
.empty {
    padding: 20px;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
    text-align: center;
    color: #6b7280;
}

/* GRID - MATCHES ORG VIEW */
.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

/* POSTER - MATCHES ORG VIEW */
.poster {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transition: 0.25s ease;
    display: flex;
    flex-direction: column;
}

.poster:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
}

/* IMAGE - MATCHES ORG VIEW (180px height) */
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
    flex: 1;
}

/* TOP */
.poster-top {
    display: flex;
    justify-content: space-between;
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

.announcement-tag {
    background: #064e3b;
}

.date {
    font-size: 12px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
}

/* TYPOGRAPHY */
.title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
}

.posted-by {
    font-size: 13px;
    color: #7f1d1d;
    margin: 6px 0;
}

.location {
    font-size: 13px;
    color: #4b5563;
    margin: 4px 0;
}

.desc {
    font-size: 13px;
    color: #6b7280;
    margin-top: 8px;
    line-height: 1.5;
}

/* ANNOUNCEMENT SPECIFIC */
.announcement-stack {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.announcement-card {
    border-left: 1px solid #e5e7eb;
}

.announcement-card:hover {
    border-left: 6px solid #7f1d1d;
}
</style>
