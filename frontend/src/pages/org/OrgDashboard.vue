<template>
    <div class="page">
        <div class="header">
            <h1>{{ title }}</h1>
            <p class="subtext">{{ description }}</p>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Members</h3>
                <p class="value">{{ totalMembers }}</p>
            </div>

            <div class="card">
                <h3>Pending Applications</h3>
                <p class="value">{{ pendingApplications }}</p>
            </div>

            <div class="card">
                <h3>Upcoming Events</h3>
                <p class="value">{{ upcomingEvents }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const title = ref('Organization Dashboard');
const description = ref(
    'Overview of your organization activity and performance'
);

const totalMembers = ref(0);
const pendingApplications = ref(0);
const upcomingEvents = ref(0);

const fetchDashboardData = async () => {
    try {
        const [membersRes, appsRes, eventsRes] = await Promise.all([
            axios.get('/api/org/dashboard/members/count', {
                withCredentials: true,
            }),
            axios.get('/api/org/dashboard/applications/pending/count', {
                withCredentials: true,
            }),
            axios.get('/api/org/dashboard/events/upcoming/count', {
                withCredentials: true,
            }),
        ]);

        totalMembers.value = membersRes?.data?.count ?? 0;
        pendingApplications.value = appsRes?.data?.count ?? 0;
        upcomingEvents.value = eventsRes?.data?.count ?? 0;
    } catch (error) {
        console.error('Dashboard fetch error:', error);

        // fallback so UI never breaks
        totalMembers.value = 0;
        pendingApplications.value = 0;
        upcomingEvents.value = 0;
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script>

<style scoped>
.page {
    padding-top: 20px;
    min-height: 100vh;
    font-family: 'Georgia', 'Times New Roman', serif;
}

/* HEADER */
.header {
    margin-bottom: 25px;
}

.header h2 {
    font-size: 28px;
    margin: 0;
    color: #1f2937;
}

.subtext {
    margin-top: 6px;
    color: #6b7280;
    font-size: 14px;
}

/* CARDS */
.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    flex: 1;
    min-width: 220px;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(200, 200, 255, 0.4);
    border-radius: 16px;
    padding: 20px;

    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    backdrop-filter: blur(10px);

    transition: all 0.25s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin: 0;
    font-size: 14px;
    color: #4b5563;
}

.value {
    margin-top: 10px;
    font-size: 32px;
    font-weight: bold;
    color: #111827;
}
</style>
