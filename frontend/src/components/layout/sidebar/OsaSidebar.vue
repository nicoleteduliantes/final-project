<template>
    <aside class="sidebar">
        <h2>ISKONEK</h2>

        <RouterLink to="/dashboard">Dashboard</RouterLink>
        <RouterLink to="/organizations">Organizations</RouterLink>
        <RouterLink to="/approvals">Approvals</RouterLink>
    </aside>
</template>

<script setup>
import { RouterLink } from "vue-router";
</script>