<template>
    <div :class="['sidebar', { collapsed }]">
        <!-- TOP SECTION -->
        <div class="top">
            <img
                v-if="!collapsed"
                src="@/assets/iskonek-logo.png"
                class="logo"
            />

            <button class="toggle" @click="collapsed = !collapsed">☰</button>
        </div>

        <!-- NAVIGATION -->
        <nav class="nav">
            <router-link to="/osa/dashboard">
                📊 <span v-if="!collapsed">Dashboard</span>
            </router-link>

            <router-link to="/osa/colleges">
                🏫 <span v-if="!collapsed">Colleges & Programs</span>
            </router-link>

            <router-link to="/osa/orgs">
                🏢 <span v-if="!collapsed">Global Orgs</span>
            </router-link>

            <router-link to="/osa/registration">
                📝 <span v-if="!collapsed">Registration</span>
            </router-link>

            <router-link to="/osa/students">
                👨‍🎓 <span v-if="!collapsed">Students</span>
            </router-link>
        </nav>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const collapsed = ref(false);
</script>

<style scoped>
/* SIDEBAR BASE */
.sidebar {
    width: 200px;
    height: 100vh;
    background: #7f1d1d; /* maroon */
    color: white;
    display: flex;
    flex-direction: column;
    padding: 10px;
    transition: width 0.3s ease;
    overflow: hidden;
}

.sidebar.collapsed {
    width: 50px;
}

/* TOP OF SIDEBAR */
.top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    margin-bottom: 15px;

    /* WHITE BACKGROUND FOR LOGO AREA */
    background: #ffffff;
    border-radius: 10px;
}

.logo {
    width: 120px;
    height: auto;

    /* ensures clean logo presentation */
    background: #ffffff;
    padding: 4px;
    border-radius: 6px;
}

/* TOGGLE BUTTON */
.toggle {
    background: transparent;
    border: none;
    color: #7f1d1d; /* maroon icon */
    font-size: 22px;
    cursor: pointer;
    padding: 4px;

    transition: 0.2s;
}

.toggle:hover {
    opacity: 0.7;
    transform: scale(1.1);
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
    padding: 10px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Georgia', serif;
    transition: 0.2s;
}

a:hover {
    background: rgba(255, 255, 255, 0.15);
}

a.router-link-active {
    background: rgba(255, 255, 255, 0.25);
}

/* COLLAPSED ALIGNMENT */
.sidebar.collapsed a {
    justify-content: center;
    padding: 12px 0;
}

/* ICON ALIGNMENT */
.sidebar.collapsed a span {
    display: none;
}
</style>
