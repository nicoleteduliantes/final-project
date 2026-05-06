<template>
    <nav class="breadcrumb">
        <span v-for="(item, index) in crumbs" :key="index">
            <!-- clickable -->
            <span
                v-if="!isLast(index) && item.path"
                class="link"
                @click="go(item.path)"
            >
                {{ item.label }}
            </span>

            <!-- current -->
            <span v-else class="current">
                {{ item.label }}
            </span>

            <span v-if="index < crumbs.length - 1"> / </span>
        </span>
    </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { buildBreadcrumb } from '@/utils/breadcrumb';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const go = (path) => {
    if (path) router.push(path);
};

const crumbs = computed(() => buildBreadcrumb(route, auth));

const isLast = (index) => index === crumbs.value.length - 1;
</script>

<style scoped>
.breadcrumb {
    display: flex;
    gap: 6px;
    font-size: 14px;
    padding: 10px 0;
    color: #6b7280;
}

.link {
    cursor: pointer;
    color: #7f1d1d;
    font-weight: 600;
}

.link:hover {
    text-decoration: underline;
}

.current {
    font-weight: 700;
    color: #064e3b;
}
</style>
