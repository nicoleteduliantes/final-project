<template>
    <div class="page">
        <h1>Discover</h1>

        <h2>Organizations</h2>

        <div class="grid">
            <div class="card" v-for="org in orgs" :key="org.org_id">
                <h3>{{ org.name }}</h3>

                <p class="category">
                    {{ org.category }}
                </p>

                <p class="desc">
                    {{ org.description }}
                </p>

                <div class="actions">
                    <RouterLink :to="'/org/' + org.org_id" class="link">
                        View
                    </RouterLink>

                    <RouterLink :to="'/apply/' + org.org_id" class="apply">
                        Apply
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get } from '@/services/apiService';

const orgs = ref([]);

onMounted(async () => {
    try {
        const res = await get('/organizations'); // backend endpoint
        orgs.value = res;
    } catch (err) {
        console.error('Failed to load organizations', err);
    }
});
</script>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}

.card {
    background: white;
    border-radius: 10px;
    padding: 16px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #7f1d1d;
}

.category {
    color: #7f1d1d;
    font-weight: 600;
    margin: 6px 0;
}

.desc {
    font-size: 14px;
    color: #444;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.link {
    color: #facc15;
    text-decoration: none;
    font-weight: 600;
}

.apply {
    color: white;
    background: #064e3b;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
}
</style>
