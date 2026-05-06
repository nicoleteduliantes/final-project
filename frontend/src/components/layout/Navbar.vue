<template>
    <nav class="navbar">
        <RouterLink to="/discover">Discover</RouterLink>

        <RouterLink v-if="role === 'student'" to="/dashboard">
            Dashboard
        </RouterLink>

        <RouterLink v-if="role === 'org'" to="/org/dashboard">
            Org Dashboard
        </RouterLink>

        <RouterLink v-if="role === 'osa'" to="/osa/dashboard">
            OSA Dashboard
        </RouterLink>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const role = computed(() => auth.role);
</script>

<style scoped>
.navbar {
    display: flex;
    gap: 16px;
    padding: 12px 20px;
    background: #7f1d1d;
}

.navbar a {
    color: white;
    text-decoration: none;
    font-weight: 600;
}

.navbar a.router-link-active {
    border-bottom: 2px solid gold;
}
</style>
