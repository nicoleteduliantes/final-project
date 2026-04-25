<template>
    <div :class="['sidebar', { collapsed }]">
        <!-- HEADER -->
        <div class="top">
            <h2 v-if="!collapsed">ISKONEK</h2>

            <button class="toggle" @click="collapsed = !collapsed">☰</button>
        </div>

        <!-- LINKS -->
        <nav>
            <router-link to="/dashboard"
                >🏠 <span v-if="!collapsed">Dashboard</span></router-link
            >
            <router-link to="/discover"
                >🔍 <span v-if="!collapsed">Discover</span></router-link
            >
            <router-link to="/memberships"
                >👥 <span v-if="!collapsed">Memberships</span></router-link
            >
            <router-link to="/participation"
                >📊 <span v-if="!collapsed">Participation</span></router-link
            >
            <router-link to="/calendar"
                >📅 <span v-if="!collapsed">Calendar</span></router-link
            >
        </nav>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const collapsed = ref(false);
</script>

<style scoped>
.sidebar {
    width: 250px;
    height: 100vh;
    background: #111827;
    color: white;
    display: flex;
    flex-direction: column;
    padding: 15px;
    transition: 0.3s;
}

.sidebar.collapsed {
    width: 70px;
}

.top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.toggle {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

nav {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

a {
    color: white;
    text-decoration: none;
    padding: 10px;
    border-radius: 6px;
    display: flex;
    gap: 10px;
    align-items: center;
}

a:hover {
    background: #1f2937;
}

a.router-link-active {
    background: #2563eb;
}
</style>
