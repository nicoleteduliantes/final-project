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
            <router-link to="/dashboard">
                🏠 <span v-if="!collapsed">Dashboard</span>
            </router-link>

            <router-link to="/discover">
                🔍 <span v-if="!collapsed">Discover</span>
            </router-link>

            <router-link to="/memberships">
                👥 <span v-if="!collapsed">Memberships</span>
            </router-link>

            <router-link to="/participation">
                📊 <span v-if="!collapsed">Participation</span>
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
/* SIDEBAR BASE */
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

    transition: width 0.3s ease;
    overflow: hidden;
    padding-bottom: 20px;
}

.sidebar.collapsed {
    width: 50px;
}

/* TOP */
.top {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 7px;
    margin-bottom: 15px;

    background: white;
    border-radius: 15px;
}

.logo {
    width: 120px;
    background: white;
    padding: 4px;
    border-radius: 6px;
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
    font-family: 'Georgia', serif;
    transition: 0.2s;
}

a:hover {
    background: white;
    color: #7f1d1d;
}

a.router-link-active {
    background: rgb(3, 74, 46);
    color: gold;
    border: 2px solid gold;
}

/* COLLAPSE STYLE */
.sidebar.collapsed a {
    justify-content: center;
}

.sidebar.collapsed span {
    display: none;
}

.sidebar.collapsed .top {
    justify-content: center;
}

/* LOGOUT */
.logout-item {
    margin-top: 220px;
    margin-bottom: 10px;

    padding: 9px;
    border-radius: 6px;

    display: flex;
    align-items: center;
    gap: 10px;

    cursor: pointer;
    transition: 0.2s;
}

.logout-item:hover {
    background: white;
    color: #7f1d1d;
}

.sidebar.collapsed .logout-item {
    justify-content: center;
}
</style>
