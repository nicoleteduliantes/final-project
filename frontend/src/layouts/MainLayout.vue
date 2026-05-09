<template>
    <div class="layout" :class="{ collapsed }">
        <component :is="sidebarComponent" v-model:collapsed="collapsed" />

        <div class="main" :class="{ collapsed }">
            <!-- <Navbar /> -->

            <div class="content">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// import Navbar from '@/components/layout/Navbar.vue';

import StudentSidebar from '@/components/layout/sidebar/StudentSidebar.vue';
import OrgSidebar from '@/components/layout/sidebar/OrgSidebar.vue';
import OSASidebar from '@/components/layout/sidebar/OsaSidebar.vue';

const role = localStorage.getItem('role') || 'student';

const collapsed = ref(false);

const sidebarComponent = computed(() => {
    if (role === 'org') return OrgSidebar;
    if (role === 'osa') return OSASidebar;
    return StudentSidebar;
});
</script>

<style scoped>
.layout {
    display: flex;
    height: 100vh;
    width: 100%;
    overflow-x: none; /* Prevents horizontal scrollbar */
}

/* MAIN CONTENT */
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;

    min-width: 0;
    margin-left: 100px;
    transition: margin-left 0.3s ease;
    padding-left: 120px;
}

/* when sidebar is collapsed */
.layout .sidebar.collapsed ~ .main {
    margin-left: 0px;
    padding-left: 50px;
}

.content {
    padding-top: 8px;
    max-width: 1400px;
}
</style>
