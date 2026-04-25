<template>
    <div class="landing">
        <h1>Welcome to Iskonek</h1>

        <RouterLink to="/student-login"> Student Login </RouterLink>

        <RouterLink to="/student-register"> Student Register </RouterLink>

        <RouterLink to="/admin-login"> Admin Login </RouterLink>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>
