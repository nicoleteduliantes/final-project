<template>
    <aside class="sidebar">
        <h2>Organization</h2>

        <RouterLink to="/dashboard">Dashboard</RouterLink>
        <RouterLink to="/events">Manage Events</RouterLink>
        <RouterLink to="/applicants">Applicants</RouterLink>
    </aside>
</template>

<script setup>
import { RouterLink } from "vue-router";
</script>