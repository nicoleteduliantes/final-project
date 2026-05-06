<template>
    <div class="page">
        <h1>Discover Organizations</h1>

        <!-- FILTER BAR -->
        <div class="filters">
            <input v-model="search" placeholder="Search organizations..." />

            <select v-model="selectedCategory">
                <option value="">All Categories</option>
                <option v-for="c in categories" :key="c" :value="c">
                    {{ c }}
                </option>
            </select>
        </div>

        <!-- GRID -->
        <div class="grid">
            <div class="card" v-for="org in filteredOrgs" :key="org.org_id">
                <!-- BANNER -->
                <div class="banner">
                    <img
                        :src="org.banner_url || fallbackBanner"
                        alt="org banner"
                    />
                </div>

                <!-- CARD BODY -->
                <div class="card-body">
                    <h3>{{ org.org_name }}</h3>

                    <p class="category">
                        {{ org.category }}
                    </p>

                    <p class="desc">
                        {{ org.description }}
                    </p>

                    <!-- ACTIONS -->
                    <div class="actions">
                        <!-- 🔥 FIXED ROUTER LINK -->
                        <RouterLink
                            :to="{
                                path: '/org/' + org.org_id,
                                query: { name: org.org_name },
                            }"
                            class="btn link"
                        >
                            View
                        </RouterLink>

                        <RouterLink
                            v-if="!org.checkMembership"
                            :to="{
                                path: '/apply/' + org.org_id,
                                query: { name: org.org_name },
                            }"
                            class="btn apply"
                        >
                            Apply
                        </RouterLink>

                        <span v-else class="badge"> Member </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get } from '@/services/apiService';

const orgs = ref([]);
const search = ref('');
const selectedCategory = ref('');

const fallbackBanner =
    'https://up.edu.ph/wp-content/uploads/2024/05/UP-Mindanao-by-Jonathan-Madrid-2048x1024.jpg';

onMounted(async () => {
    try {
        const res = await get('/organizations');
        orgs.value = res.data ?? res;
    } catch (err) {
        console.error('Failed to load organizations', err);
    }
});

/* AUTO CATEGORIES */
const categories = computed(() => {
    const set = new Set(orgs.value.map((o) => o.category));
    return [...set];
});

/* FILTER LOGIC */
const filteredOrgs = computed(() => {
    return orgs.value.filter((org) => {
        const matchesSearch =
            org.org_name?.toLowerCase().includes(search.value.toLowerCase()) ||
            org.description?.toLowerCase().includes(search.value.toLowerCase());

        const matchesCategory =
            !selectedCategory.value || org.category === selectedCategory.value;

        return matchesSearch && matchesCategory;
    });
});
</script>

<style scoped>
/* FILTER BAR */
.filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

input,
select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    width: 100%;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
}

/* CARD */
.card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: 0.2s ease;
}

.card:hover {
    transform: translateY(-3px);
}

/* BANNER */
.banner {
    height: 120px;
    overflow: hidden;
}

.banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* BODY */
.card-body {
    padding: 14px;
}

.category {
    color: #7f1d1d;
    font-weight: 600;
    font-size: 13px;
    margin: 5px 0;
}

.desc {
    font-size: 13px;
    color: #555;
    margin-bottom: 10px;
}

/* ACTION ROW */
.actions {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

/* SHARED BUTTON BASE */
.btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 6px 10px;
    font-size: 13px;
    line-height: 1;
    border-radius: 6px;

    text-decoration: none;
    font-weight: 600;

    border: 1px solid transparent;
    box-sizing: border-box;

    transition: 0.2s ease;
    text-align: center;
}

/* VIEW (OUTLINE) */
.link {
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}

.link:hover {
    background: #7f1d1d;
    color: white;
}

/* APPLY (SOLID) */
.apply {
    background: #7f1d1d;
    color: white;
    border: 2px solid #7f1d1d;
}

.apply:hover {
    background: #064e3b;
    color: gold;
    border: gold solid 2px;
}

/* MEMBER BADGE */
.badge {
    flex: 1;
    text-align: center;
    font-size: 12px;
    background: #e5e7eb;
    padding: 6px 8px;
    border-radius: 6px;
}
</style>
