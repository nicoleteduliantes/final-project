<template>
    <div class="page map-page">
        <div class="header">
            <h1>Discover</h1>
            <p class="subtext">
                Explore the map and join organizations that match your interests
            </p>
        </div>

        <!-- MODAL -->
        <div
            v-if="selectedOrg"
            class="modal-overlay"
            @click.self="selectedOrg = null"
        >
            <div class="modal-content">
                <button class="close-modal" @click="selectedOrg = null">
                    ✕
                </button>

                <img
                    :src="selectedOrg.banner_url || fallbackBanner"
                    class="modal-banner"
                />

                <div class="modal-body">
                    <h2>{{ selectedOrg.org_name }}</h2>

                    <span
                        class="modal-cat"
                        :style="{ color: getColor(selectedOrg.category) }"
                    >
                        {{ selectedOrg.category }}
                    </span>

                    <p class="modal-description">
                        {{
                            selectedOrg.description ||
                            'No detailed description provided.'
                        }}
                    </p>

                    <div class="modal-actions">
                        <button
                            v-if="!isMember(selectedOrg.org_id)"
                            class="apply-btn"
                            @click="handleApply(selectedOrg.org_id)"
                        >
                            Apply to Join
                        </button>

                        <button v-else class="member-status" disabled>
                            ✓ Already a Member
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOPBAR (SEARCH ONLY) -->
        <header class="topbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input v-model="search" placeholder="Search organizations..." />
            </div>
        </header>

        <div class="layout">
            <!-- SIDEBAR -->
            <aside class="sidebar" :class="{ collapsed: !categoriesOpen }">
                <button
                    class="sidebar-toggle"
                    @click="categoriesOpen = !categoriesOpen"
                >
                    <span v-if="categoriesOpen">CATEGORIES</span>
                    <span class="arrow" :class="{ open: !categoriesOpen }">
                        ❮
                    </span>
                </button>

                <div v-show="categoriesOpen" class="cat-list">
                    <button
                        class="cat-btn"
                        :class="{ active: selectedCategory === '' }"
                        @click="selectedCategory = ''"
                    >
                        All Categories
                    </button>

                    <button
                        v-for="c in categories"
                        :key="c"
                        class="cat-btn"
                        :class="{ active: selectedCategory === c }"
                        @click="selectedCategory = c"
                        :style="{
                            borderLeft: '5px solid ' + getColor(c),
                            backgroundColor:
                                selectedCategory === c
                                    ? getColor(c)
                                    : '#ffffff',
                            color: selectedCategory === c ? 'white' : '#333',
                        }"
                    >
                        {{ c }}
                    </button>
                </div>
            </aside>

            <!-- MAP -->
            <main class="map-container">
                <div class="walkway">
                    <div
                        v-for="(row, rowIndex) in groupedRows"
                        :key="rowIndex"
                        class="lane"
                    >
                        <!-- LEFT SIDE -->
                        <div class="lane-side left">
                            <div
                                v-for="org in row.slice(0, 2)"
                                :key="org.org_id"
                                class="stall"
                                :style="{
                                    borderTop:
                                        '6px solid ' + getColor(org.category),
                                }"
                            >
                                <!-- ALWAYS VISIBLE -->
                                <div class="stall-name">
                                    {{ org.org_name }}
                                </div>

                                <span
                                    class="stall-cat"
                                    :style="{ color: getColor(org.category) }"
                                >
                                    {{ org.category }}
                                </span>

                                <!-- ONLY SHOW WHEN VIEW ORGS CLICKED -->
                                <div v-if="focusedRow === rowIndex">
                                    <div class="stall-banner">
                                        <img
                                            :src="
                                                org.banner_url || fallbackBanner
                                            "
                                        />
                                    </div>

                                    <div class="stall-desc">
                                        {{ org.description }}
                                    </div>
                                </div>

                                <button
                                    @click="selectedOrg = org"
                                    class="stall-btn"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>

                        <!-- PATH -->
                        <div class="path">
                            <div class="row-tag">
                                ROW {{ String.fromCharCode(65 + rowIndex) }}
                            </div>

                            <button
                                class="row-focus-btn"
                                @click="toggleRowFocus(rowIndex)"
                            >
                                {{
                                    focusedRow === rowIndex
                                        ? 'Close'
                                        : 'View Orgs'
                                }}
                            </button>

                            <div class="path-line"></div>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="lane-side right">
                            <div
                                v-for="org in row.slice(2, 5)"
                                :key="org.org_id"
                                class="stall"
                                :style="{
                                    borderTop:
                                        '6px solid ' + getColor(org.category),
                                }"
                            >
                                <!-- ALWAYS VISIBLE -->
                                <div class="stall-name">
                                    {{ org.org_name }}
                                </div>

                                <span
                                    class="stall-cat"
                                    :style="{ color: getColor(org.category) }"
                                >
                                    {{ org.category }}
                                </span>

                                <!-- ONLY SHOW WHEN VIEW ORGS CLICKED -->
                                <div v-if="focusedRow === rowIndex">
                                    <div class="stall-banner">
                                        <img
                                            :src="
                                                org.banner_url || fallbackBanner
                                            "
                                        />
                                    </div>

                                    <div class="stall-desc">
                                        {{ org.description }}
                                    </div>
                                </div>

                                <button
                                    @click="selectedOrg = org"
                                    class="stall-btn"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get } from '@/services/apiService';

const orgs = ref([]);
const search = ref('');
const selectedCategory = ref('');
const focusedRow = ref(null);
const selectedOrg = ref(null);
const categoriesOpen = ref(true);

const userMemberships = ref([102, 105]);

const fallbackBanner =
    'https://up.edu.ph/wp-content/uploads/2024/05/UP-Mindanao-by-Jonathan-Madrid-2048x1024.jpg';

/* FETCH */
onMounted(async () => {
    try {
        const res = await get('/organizations');
        orgs.value = res.data ?? res;
    } catch (err) {
        console.error(err);
    }
});

/* TOGGLE ROW */
const toggleRowFocus = (index) => {
    focusedRow.value = focusedRow.value === index ? null : index;
};

/* MEMBERSHIP */
const isMember = (id) => userMemberships.value.includes(id);

/* APPLY */
const handleApply = (id) => {
    alert(`Applied to org ${id}`);
};

/* COLORS */
const categories = computed(() => [
    ...new Set(orgs.value.map((o) => o.category).filter(Boolean)),
]);

const palette = ['#EA7317', '#FEC601', '#9CD08F', '#3DA5D9', '#5941A9'];

const getColor = (cat) => {
    if (!cat) return '#7f1d1d';
    const i = categories.value.indexOf(cat);
    return palette[i % palette.length] || '#7f1d1d';
};

/* FILTER (FIXED SEARCH) */
const filteredOrgs = computed(() => {
    const q = search.value.toLowerCase().trim();

    return orgs.value.filter((o) => {
        const name = (o.org_name || '').toLowerCase();
        const desc = (o.description || '').toLowerCase();

        const matchesSearch = !q || name.includes(q) || desc.includes(q);

        const matchesCat =
            !selectedCategory.value || o.category === selectedCategory.value;

        return matchesSearch && matchesCat;
    });
});

/* GROUP ROWS */
const groupedRows = computed(() => {
    const rows = [];
    const data = filteredOrgs.value;

    for (let i = 0; i < data.length; i += 5) {
        rows.push(data.slice(i, i + 5));
    }

    return rows;
});
</script>

<style scoped>
* {
    box-sizing: border-box;
}
/* HEADER */
.header {
    padding-top: 0px;
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
.category-select {
    border-left: #7f1d1d solid 3px;
}
/* SIDEBAR */
.sidebar {
    width: 220px;
    background: white;
    padding: 20px;
    border-radius: 16px;
    position: sticky;
    top: 24px;
    flex-shrink: 0;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);

    transition: width 0.3s ease;
    overflow: hidden;
}

/* COLLAPSED */
.sidebar.collapsed {
    width: 60px;
    padding: 20px 12px;
}

/* TOGGLE */
.sidebar-toggle {
    width: 100%;
    border: none;
    background: none;
    padding: 6px 4px;
    margin-bottom: 16px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    letter-spacing: 0.1em;

    cursor: pointer;
}

/* BIGGER ARROW BUTTON */
.arrow {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 18px;
    font-weight: 900;

    border-radius: 10px;

    background: #f1f5f9;
    color: #7f1d1d;

    transition: all 0.2s ease;
}

/* hover effect */
.sidebar-toggle:hover .arrow {
    background: #7f1d1d;
    color: white;
    transform: scale(1.05);
}

/* rotate when collapsed */
.arrow.open {
    transform: rotate(180deg);
}

/* CATEGORY LIST */
.cat-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cat-btn {
    width: 100%;
    background: #ffffff;
    color: #333;
    text-align: left;
    padding: 12px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    white-space: nowrap;
}
/* MODAL */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    background: rgba(0, 0, 0, 0.6);

    display: flex;
    justify-content: center;
    align-items: center;

    z-index: 99999;
    backdrop-filter: blur(4px);
}

.modal-content {
    background: white;
    width: 90%;
    max-width: 500px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

.close-modal {
    position: absolute;
    top: 12px;
    right: 12px;

    width: 34px;
    height: 34px;
    border-radius: 50%;

    background: white;
    color: #7f1d1d;

    border: 1px solid #e5e7eb;

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: 900;
    font-size: 14px;

    cursor: pointer;
    z-index: 10;

    transition: 0.2s ease;
}

.close-modal:hover {
    background: #7f1d1d;
    color: white;
    transform: scale(1.05);
}

.modal-banner {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.modal-body {
    padding: 24px;
}

.modal-body h2 {
    margin: 0 0 8px;
}

.modal-cat {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    display: block;
    margin-bottom: 16px;
}

.modal-description {
    color: #475569;
    line-height: 1.6;
    font-size: 14px;
    margin-bottom: 24px;
}

.apply-btn,
.member-status {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    font-weight: 700;
}

.apply-btn {
    background: #7f1d1d;
    border: 3px solid #7f1d1d;
    color: white;
    cursor: pointer;
}

.apply-btn:hover {
    background: #034a2e;
    border: 3px solid gold;
    color: gold;
}

.member-status {
    background: #f1f5f9;
    color: #64748b;
    border: none;
}

/* PAGE */
.map-page {
    background: white;
    padding: clamp(16px, 2vw, 32px);
    min-height: 100vh;
    overflow-x: hidden;
    width: 100%;
}

/* TOPBAR */
.topbar {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 24px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.search-box {
    flex: 1;
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

.layout {
    display: flex;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
    max-width: 100%;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: white;
    padding: 20px;
    border-radius: 16px;
    position: sticky;
    top: 24px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.sidebar h3 {
    font-size: 11px;
    color: #64748b;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
}

.cat-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cat-btn {
    width: 100%;
    text-align: left;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
    cursor: pointer;
    font-size: 13px;
}

/* MAP CONTAINER */
.map-container {
    flex: 1;
    background: white;
    padding: clamp(18px, 2vw, 36px);
    border-radius: 20px;
    border: 1px solid #e2e8f0;

    overflow-x: auto;
    overflow-y: hidden;

    max-width: 100%;
}

/* WALKWAY */
.walkway {
    display: flex;
    flex-direction: column;
    gap: clamp(24px, 2vw, 40px);

    width: max-content;
    min-width: 100%;
}

/* LANES */
.lane {
    display: grid;
    grid-template-columns: auto auto auto;
    align-items: center;
    gap: clamp(20px, 2vw, 40px);

    width: max-content;
}

.lane.dimmed {
    opacity: 0.25;
    filter: grayscale(0.5);
}

.lane.focused {
    transform: scale(1.02);
}

/* LEFT + RIGHT SIDES */
.lane-side {
    display: flex;
    gap: clamp(12px, 1vw, 22px);
}

.lane-side.left {
    justify-content: flex-start;
}

.lane-side.right {
    justify-content: flex-start;
}

/* STALLS */
.stall {
    width: clamp(170px, 12vw, 240px);

    background: white;
    border-radius: 12px;
    padding: clamp(12px, 1vw, 18px);

    border: 1px solid #f1f5f9;

    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);

    display: flex;
    flex-direction: column;
    transition: 0.3s;
}

.stall:hover {
    transform: translateY(-4px);
}

.stall-banner img {
    width: 100%;
    height: clamp(85px, 8vw, 120px);
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 12px;
}

.stall-name {
    font-size: clamp(12px, 0.9vw, 15px);
    font-weight: 700;
    margin-bottom: 4px;
    min-height: 2.4em;
}

.stall-cat {
    font-size: clamp(9px, 0.7vw, 11px);
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 12px;
    display: block;
}

.stall-desc {
    font-size: clamp(10px, 0.8vw, 12px);
    color: #64748b;
    line-height: 1.4;
    margin-bottom: 14px;
}

.stall-btn {
    text-align: center;
    padding: 8px;
    color: #7f1d1d;
    border: 1.5px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.stall-btn:hover {
    background: #7f1d1d;
    color: white;
}

/* PATH */
.path {
    width: clamp(90px, 8vw, 130px);

    display: flex;
    flex-direction: column;
    align-items: center;

    flex-shrink: 0;
}

.row-tag {
    font-size: 10px;
    font-weight: 800;
    color: #7f1d1d;
    margin-bottom: 4px;
}

.row-focus-btn {
    background: white;
    border: 1px solid #7f1d1d;
    color: #7f1d1d;

    font-size: 10px;
    font-weight: 700;

    padding: 4px 8px;
    border-radius: 20px;

    cursor: pointer;
    margin-bottom: 8px;
}

.row-focus-btn.active {
    background: #1e293b;
    color: white;
    border-color: #1e293b;
}

.row-focus-btn:hover {
    background: #7f1d1d;
    color: white;
}

.path-line {
    width: 2px;
    height: clamp(80px, 8vw, 120px);

    background: repeating-linear-gradient(
        to bottom,
        #cbd5e1,
        #cbd5e1 6px,
        transparent 6px,
        transparent 12px
    );
}

/* MOBILE */
@media (max-width: 1100px) {
    .layout {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        position: static;
    }

    .lane {
        flex-direction: column;
    }

    .lane-side {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }

    .path-line {
        display: none;
    }
}
</style>
