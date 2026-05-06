<template>
    <div :class="['sidebar', { collapsed }]">
        <!-- TOP -->
        <div class="top">
            <img
                v-if="!collapsed"
                src="@/assets/iskonek-logo.png"
                class="logo"
            />

            <button class="toggle" @click="collapsed = !collapsed">☰</button>
        </div>

        <!-- NAV -->
        <nav class="nav">
            <router-link to="/org/dashboard">
                📊 <span v-if="!collapsed">Dashboard</span>
            </router-link>

            <router-link to="/org/members">
                👥 <span v-if="!collapsed">Members</span>
            </router-link>

            <router-link to="/org/applications">
                📄 <span v-if="!collapsed">Applications</span>
            </router-link>

            <router-link to="/org/events">
                🎉 <span v-if="!collapsed">Events</span>
            </router-link>

            <router-link to="/org/events/new">
                ➕ <span v-if="!collapsed">New Event</span>
            </router-link>

            <router-link to="/org/attendance">
                📌 <span v-if="!collapsed">Attendance</span>
            </router-link>
        </nav>

        <!-- LOGOUT -->
        <div class="logout-item" @click="logout">
            🔓 <span v-if="!collapsed">Logout</span>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const collapsed = defineModel();

const router = useRouter();
const auth = useAuthStore();

const logout = () => {
    auth.logout();
    localStorage.clear();
    router.replace('/');
};
</script>

<style scoped>
.sidebar {
    width: 200px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;

    background: #7f1d1d;
    color: white;

    display: flex;
    flex-direction: column;

    padding: 10px;
    overflow: hidden;

    transition: width 0.3s ease;
}

.sidebar.collapsed {
    width: 50px;
}

/* TOP */
.top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    background: white;
    border-radius: 12px;

    padding: 7px;
    margin-bottom: 15px;
}

.logo {
    width: 120px;
}

/* TOGGLE */
.toggle {
    background: transparent;
    border: none;
    color: #7f1d1d;
    font-size: 22px;
    cursor: pointer;
}

/* NAV */
.nav {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

/* LINKS */
a {
    color: white;
    text-decoration: none;
    padding: 9px;
    border-radius: 6px;

    display: flex;
    align-items: center;
    gap: 10px;
}

a:hover {
    background: white;
    color: #7f1d1d;
}

a.router-link-active {
    background: rgb(3, 74, 46);
    color: gold;
}

/* COLLAPSED TEXT ONLY */
.sidebar.collapsed span {
    display: none;
}

.sidebar.collapsed a {
    justify-content: center;
}

/* LOGOUT (UNIFIED FIX) */
.logout-item {
    margin-top: auto;
    margin-bottom: 10px;

    padding: 10px;
    cursor: pointer;

    display: flex;
    align-items: center;
    gap: 10px;
}

.logout-item:hover {
    background: white;
    color: #7f1d1d;
}

.sidebar.collapsed .logout-item {
    justify-content: center;
}
</style>
