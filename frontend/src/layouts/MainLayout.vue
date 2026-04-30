<template>
    <div class="layout">
        <component :is="sidebarComponent" />

        <div class="main">
            <Navbar />

            <Breadcrumb />

            <div class="content">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

import Navbar from '@/components/layout/Navbar.vue';
import Breadcrumb from '@/components/layout/Breadcrumb.vue';

import StudentSidebar from '@/components/layout/sidebar/StudentSidebar.vue';
import OrgSidebar from '@/components/layout/sidebar/OrgSidebar.vue';
import OSASidebar from '@/components/layout/sidebar/OsaSidebar.vue';

const role = localStorage.getItem('role') || 'student';

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
}

.main {
    flex: 1;
    display: flex;
    flex-direction: column;

    padding: 20px 24px;
    margin-left: 8px;
    background: #f9fafb;
}

.content {
    padding-top: 8px;
}
</style>
