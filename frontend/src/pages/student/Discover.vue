<template>
    <div class="page map-page">
        <!-- HEADER -->
        <div class="header">
            <h1>Discover</h1>
            <p class="subtext">
                Explore the map and join organizations that match your interests
            </p>
        </div>

        <!-- APPLICATION MODAL -->
        <div
            v-if="showApplyModal"
            class="modal-overlay"
            @click.self="closeApplyModal"
        >
            <div class="modal-content">
                <button class="close-modal" @click="closeApplyModal">✕</button>

                <ApplicationFormModal
                    :org="selectedOrgForApply"
                    @submitted="handleApplicationSuccess"
                    @close="closeApplyModal"
                />
            </div>
        </div>

        <!-- ORG DETAILS MODAL -->
        <OrgDetailsModal
            v-if="selectedOrg && selectedOrg.org_id"
            :org="selectedOrg"
            :fallbackBanner="fallbackBanner"
            :isMember="isMember"
            :getColor="getColor"
            @close="closeOrgModal"
            @apply="openApplyModal"
        />

        <!-- SEARCH -->
        <header class="topbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input v-model="search" placeholder="Search organizations..." />
            </div>
        </header>

        <!-- LAYOUT -->
        <div class="layout">
            <CatSidebar
                :categories="categories"
                :selectedCategory="selectedCategory"
                :categoriesOpen="categoriesOpen"
                :getColor="getColor"
                @toggle="categoriesOpen = !categoriesOpen"
                @select="selectedCategory = $event"
            />

            <main class="map-container">
                <div class="walkway">
                    <OrgLane
                        v-for="(row, rowIndex) in groupedRows"
                        :key="rowIndex"
                        :row="row"
                        :rowIndex="rowIndex"
                        :focusedRow="focusedRow"
                        :getColor="getColor"
                        :fallbackBanner="fallbackBanner"
                        @toggle-row="toggleRowFocus"
                        @view-org="openOrgModal"
                    />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get } from '@/services/apiService';

import CatSidebar from '@/components/CatSidebar.vue';
import OrgLane from '@/components/OrgLane.vue';
import OrgDetailsModal from '@/components/OrgDetailsModal.vue';
import ApplicationFormModal from '@/components/ApplicationFormModal.vue';

const orgs = ref([]);
const search = ref('');
const selectedCategory = ref('');
const selectedOrg = ref(null);
const focusedRow = ref(null);

const showApplyModal = ref(false);
const selectedOrgForApply = ref(null);
const categoriesOpen = ref(true);

const userMemberships = ref([]);

const fallbackBanner =
    'https://up.edu.ph/wp-content/uploads/2024/05/UP-Mindanao-by-Jonathan-Madrid-2048x1024.jpg';

onMounted(async () => {
    const res = await get('/organizations');
    orgs.value = res.data ?? res;

    const mem = await get('/memberships');
    userMemberships.value = mem.data ?? mem;
});

/* MODAL HANDLERS (FIXED) */

const openOrgModal = (org) => {
    selectedOrg.value = org;
};

const closeOrgModal = () => {
    selectedOrg.value = null;
};

const openApplyModal = (org) => {
    selectedOrgForApply.value = org;
    showApplyModal.value = true;
    selectedOrg.value = null;
};

const closeApplyModal = () => {
    showApplyModal.value = false;
    selectedOrgForApply.value = null;
};

const handleApplicationSuccess = () => {
    closeApplyModal();
    alert('Application submitted successfully!');
};

const toggleRowFocus = (index) => {
    focusedRow.value = focusedRow.value === index ? null : index;
};

/* =======================
   DATA HELPERS
======================= */

const categories = computed(() => [
    ...new Set(orgs.value.map((o) => o.category).filter(Boolean)),
]);

const palette = ['#EA7317', '#FEC601', '#9CD08F', '#3DA5D9', '#5941A9'];

const getColor = (cat) => {
    if (!cat) return '#7f1d1d';
    const i = categories.value.indexOf(cat);
    return palette[i % palette.length];
};

const filteredOrgs = computed(() => {
    const q = search.value.toLowerCase();

    return orgs.value.filter((o) => {
        const matchSearch =
            o.org_name?.toLowerCase().includes(q) ||
            o.description?.toLowerCase().includes(q);

        const matchCategory =
            !selectedCategory.value || o.category === selectedCategory.value;

        return matchSearch && matchCategory;
    });
});

const groupedRows = computed(() => {
    const rows = [];
    for (let i = 0; i < filteredOrgs.value.length; i += 5) {
        rows.push(filteredOrgs.value.slice(i, i + 5));
    }
    return rows;
});

const isMember = (orgId) => {
    return userMemberships.value.includes(Number(orgId));
};
</script>

<style scoped>
* {
    box-sizing: border-box;
}

.map-page {
    background: white;
    padding: clamp(16px, 2vw, 32px);
    min-height: 100vh;
    width: 100%;
}

/* HEADER */
.header {
    margin-bottom: 18px;
}

h1 {
    margin: 0;
    font-size: clamp(26px, 2vw, 38px);
    font-weight: 900;
    color: #7f1d1d;
}

.subtext {
    margin-top: 4px;
    color: #6b7280;
    font-size: clamp(14px, 1vw, 16px);
}

/* SEARCH */
.topbar {
    margin-bottom: 24px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
}

.search-box input {
    width: 100%;
    padding: 12px 12px 12px 42px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

/* LAYOUT */
.layout {
    display: flex;
    gap: 24px;
    align-items: flex-start;
}

/* MAP */
.map-container {
    flex: 1;
    background: white;
    padding: clamp(18px, 2vw, 36px);
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow-x: auto;
}

.walkway {
    display: flex;
    flex-direction: column;
    gap: 30px;
}
</style>
