<template>
    <div class="page">
        <h1>{{ org.name }}</h1>

        <p>{{ org.description }}</p>

        <RouterLink :to="'/apply/' + org.id" class="apply-btn">
            Apply to Organization
        </RouterLink>
    </div>
</template>

<script setup>
import { useRoute } from 'vue-router';

const route = useRoute();

const org = {
    id: route.params.id,
    name: 'AWS Cloud Club',
    description: 'Official AWS student organization',
};
</script>

<style scoped>
.page {
    padding: 20px;
}

.apply-btn {
    background: #059669;
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    margin-top: 15px;
}

.apply-btn:hover {
    background: #047857;
}
</style>
