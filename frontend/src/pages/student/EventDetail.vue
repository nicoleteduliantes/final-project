<template>
    <div class="page" v-if="event">
        <h1>{{ event.name }}</h1>

        <p class="desc">
            {{ event.description }}
        </p>

        <button class="join" @click="join">Join Event</button>
    </div>

    <div v-else class="loading">Loading event...</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { get } from '@/services/apiService';

const router = useRouter();
const route = useRoute();

const event = ref(null);

onMounted(async () => {
    try {
        const res = await get(`/events/${route.params.id}`);
        event.value = res;
    } catch (err) {
        console.error('Failed to load event', err);
    }
});

const join = () => {
    router.push('/participate/' + event.value.event_id);
};
</script>

<style scoped>
.desc {
    margin: 15px 0;
    font-size: 15px;
}

.join {
    background: #064e3b;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
}

.join:hover {
    opacity: 0.9;
}

.loading {
    padding: 20px;
}
</style>
