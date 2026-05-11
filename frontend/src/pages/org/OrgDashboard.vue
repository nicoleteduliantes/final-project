<template>
    <div class="page">
        <h1>{{ userOrgName }}</h1>
        <h2>Dashboard</h2>

        <!-- ANNOUNCEMENTS -->
        <section class="section">
            <h2 class="section-title">Announcements</h2>

            <div v-if="announcements.length === 0" class="empty">
                No announcements found.
            </div>

            <div v-else>
                <div
                    class="announcement-card poster"
                    v-for="ann in announcements"
                    :key="ann.announcement_id"
                    style="margin-bottom: 12px"
                >
                    <div class="poster-content">
                        <div class="poster-top">
                            <span class="tag">ANNOUNCEMENT</span>
                            <span class="date">{{ ann.date_posted }}</span>
                        </div>

                        <h3 class="title" style="margin-top: 8px">
                            {{ ann.title }}
                        </h3>

                        <p
                            class="location"
                            style="font-weight: 700; color: #7f1d1d"
                        >
                            Posted by:
                            {{
                                ann.osa_id
                                    ? 'Office of the Student Affairs'
                                    : ann.organization?.org_name ||
                                      'Organization'
                            }}
                        </p>

                        <p class="desc">
                            {{ ann.content }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- UPCOMING EVENTS -->
        <section class="section">
            <h2 class="section-title">Upcoming Events</h2>

            <!-- EMPTY STATE -->
            <div v-if="events.length === 0" class="empty">
                No upcoming events found.
            </div>

            <!-- POSTER GRID -->
            <div v-else class="poster-grid">
                <div
                    class="poster"
                    v-for="event in events"
                    :key="event.event_id"
                >
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
                        <!-- TOP -->
                        <div class="poster-top">
                            <span class="tag">{{ event.event_host }}</span>
                            <span class="date">{{ event.date }}</span>
                        </div>

                        <!-- TITLE -->
                        <h3 class="title">
                            {{ event.event_name }}
                        </h3>

                        <!-- LOCATION -->
                        <p class="location">📍 {{ event.location }}</p>

                        <!-- DESCRIPTION -->
                        <p class="desc">
                            {{ event.description }}
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
import { get } from '@/services/apiService';

const events = ref([]);
const announcements = ref([]);
const userOrgName = ref('Organization');

const fetchData = async () => {
    try {
        const userData = JSON.parse(localStorage.getItem('user'));

        if (userData && userData.org_name) {
            userOrgName.value = userData.org_name;
        } else if (userData && userData.role === 'admin') {
            userOrgName.value = 'Office of Student Affairs';
        }

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
/* TITLE */
.page-title {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 16px;
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
    padding: 16px;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
    text-align: center;
    color: #6b7280;
}

/* GRID */
.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 18px;
}

/* POSTER */
.poster {
    background: white;
    border-radius: 16px;
    overflow: hidden;

    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);

    display: flex;
    flex-direction: column;

    transition: 0.25s ease;
}

.poster:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
}

/* IMAGE */
.poster-image {
    height: 160px;
    overflow: hidden;
}

.poster-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* CONTENT */
.poster-content {
    padding: 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

/* TOP */
.poster-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* TAG */
.tag {
    background: #7f1d1d;
    color: white;

    padding: 3px 10px;
    border-radius: 999px;

    font-size: 10px;
    font-weight: 700;
}

/* DATE */
.date {
    font-size: 12px;
    color: #6b7280;
}

/* TITLE */
.title {
    margin: 10px 0 4px;
    font-size: 18px;
    font-weight: 800;
}

/* LOCATION */
.location {
    font-size: 13px;
    color: #4b5563;
    margin-bottom: 6px;
}

/* DESCRIPTION */
.desc {
    font-size: 13px;
    color: #4b5563;
    line-height: 1.5;
}

/* ACTION */
.actions {
    margin-top: auto;
    padding-top: 12px;
}

/* BUTTON */
.btn {
    display: flex;
    justify-content: center;
    align-items: center;

    width: 100%;
    padding: 8px;

    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;

    text-decoration: none;

    box-sizing: border-box;
}

/* LINK */
.link {
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}

.link:hover {
    background: #7f1d1d;
    color: white;
}

/* ANNOUNCEMENT */
.announcement-card {
    padding: 14px;
    font-size: 14px;
}
</style>
