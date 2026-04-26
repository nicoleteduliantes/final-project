<template>
    <div class="page" v-if="org">
        <h1>{{ org.name }}</h1>

        <p class="desc">
            {{ org.description }}
        </p>

        <RouterLink :to="'/apply/' + org.org_id" class="apply-btn">
            Apply to Organization
        </RouterLink>
    </div>

    <div v-else class="loading">Loading organization...</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { get } from '@/services/apiService';

const route = useRoute();
const org = ref(null);

onMounted(async () => {
    try {
        const res = await get(`/organizations/${route.params.id}`);
        org.value = res;
    } catch (err) {
        console.error('Failed to load org', err);
    }
});
</script>

<style scoped>
.page {
    padding: 20px;
}

.desc {
    margin-top: 10px;
    font-size: 15px;
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

.loading {
    padding: 20px;
}
</style>
