<template>
    <header class="navbar">
        <nav class="breadcrumb">
            <span v-for="(item, index) in trail" :key="index">
                <span
                    v-if="item.path && index !== trail.length - 1"
                    class="link"
                    @click="go(item.path)"
                >
                    {{ item.label }}
                </span>

                <span v-else>
                    {{ item.label }}
                </span>

                <span v-if="index < trail.length - 1"> / </span>
            </span>
        </nav>

        <SearchBar />
    </header>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import SearchBar from './SearchBar.vue';

const route = useRoute();
const router = useRouter();

const go = (path) => {
    if (path) router.push(path);
};

const orgNames = {
    1: 'AWS Cloud Club',
    2: 'Google Developer Group',
};

const eventNames = {
    1: 'Hackathon 2026',
    2: 'Cloud Workshop',
};

const trail = computed(() => {
    const path = route.path;
    const id = route.params.id;

    // DASHBOARD EVENT DETAIL
    if (path.startsWith('/dashboard/event/')) {
        return [
            { label: 'Dashboard', path: '/dashboard' },
            { label: eventNames[id] || `Event #${id}`, path: null },
        ];
    }

    // APPLY FLOW
    if (path.startsWith('/apply/')) {
        return [
            { label: 'Discover', path: '/discover' },
            {
                label: orgNames[id] || `Org #${id}`,
                path: `/org/${id}`,
            },
            { label: 'Application Form', path: null },
        ];
    }

    // ORG PROFILE (strict match)
    if (path.startsWith('/org/') && id) {
        return [
            { label: 'Discover', path: '/discover' },
            {
                label: orgNames[id] || `Org #${id}`,
                path: null,
            },
        ];
    }

    if (path === '/discover') {
        return [{ label: 'Discover', path: null }];
    }

    if (path.startsWith('/dashboard')) {
        return [{ label: 'Dashboard', path: null }];
    }

    return [{ label: 'Page', path: null }];
});
</script>

<style scoped>
.navbar {
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}
</style>
