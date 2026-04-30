<template>
    <nav class="breadcrumb">
        <span v-for="(item, index) in crumbs" :key="index">
            <!-- clickable ONLY if not last -->
            <span
                v-if="!isLast(index) && item.path"
                class="link"
                @click="go(item.path)"
            >
                {{ item.label }}
            </span>

            <!-- current page -->
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
import { buildBreadcrumb } from '@/utils/breadcrumb';

const route = useRoute();
const router = useRouter();

const go = (path) => {
    if (path) router.push(path);
};

const metaResolver = (last) => {
    if (!last) return '';

    return last.replace(/[-_]/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
};

const crumbs = computed(() => buildBreadcrumb(route, metaResolver));

const isLast = (index) => index === crumbs.value.length - 1;
</script>

<style scoped>
.breadcrumb {
    display: flex;
    gap: 6px;
    font-size: 14px;
    padding: 10px 0;
}

.link {
    cursor: pointer;
    color: #2563eb;
}

.link:hover {
    text-decoration: underline;
}

.current {
    font-weight: 600;
    color: rgb(3, 74, 46);
}
</style>
