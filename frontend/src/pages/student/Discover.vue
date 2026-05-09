<template>
    <div class="page map-page">
        <div class="header">
            <h1>Discover</h1>
            <p class="subtext">
                Explore the map and join organizations that match your interests
            </p>
        </div>

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

        <header class="topbar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input v-model="search" placeholder="Search organizations..." />
            </div>

            <select v-model="selectedCategory" class="category-select">
                <option value="">All Categories</option>
                <option v-for="c in categories" :key="c" :value="c">
                    {{ c }}
                </option>
            </select>

            <div class="active-status">
                Viewing:
                <strong :style="{ color: getColor(selectedCategory) }">
                    {{ selectedCategory || 'All Organizations' }}
                </strong>
            </div>
        </header>

        <div class="layout">
            <aside class="sidebar">
                <h3>CATEGORIES</h3>
                <div class="cat-list">
                    <button
                        class="cat-btn"
                        :class="{ active: selectedCategory === '' }"
                        @click="selectedCategory = ''"
                        :style="
                            selectedCategory === ''
                                ? { backgroundColor: '#7f1d1d', color: 'white' }
                                : {}
                        "
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
                                selectedCategory === c ? getColor(c) : 'white',
                            color:
                                selectedCategory === c
                                    ? c.includes('Cultural')
                                        ? '#000'
                                        : 'white'
                                    : '#333',
                        }"
                    >
                        {{ c }}
                    </button>
                </div>
            </aside>

            <main class="map-container">
                <div class="walkway">
                    <div
                        v-for="(row, rowIndex) in groupedRows"
                        :key="rowIndex"
                        class="lane"
                        :class="{
                            focused: focusedRow === rowIndex,
                            dimmed:
                                focusedRow !== null && focusedRow !== rowIndex,
                        }"
                    >
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
                                <div
                                    v-if="focusedRow === rowIndex"
                                    class="stall-banner"
                                >
                                    <img
                                        :src="org.banner_url || fallbackBanner"
                                    />
                                </div>
                                <div class="stall-name">{{ org.org_name }}</div>
                                <span
                                    class="stall-cat"
                                    :style="{ color: getColor(org.category) }"
                                >
                                    {{ org.category }}
                                </span>

                                <div
                                    v-if="focusedRow === rowIndex"
                                    class="stall-desc"
                                >
                                    {{ org.description }}
                                </div>

                                <button
                                    v-if="focusedRow === rowIndex"
                                    @click="selectedOrg = org"
                                    class="stall-btn"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>

                        <div class="path">
                            <div class="row-tag">
                                ROW {{ String.fromCharCode(65 + rowIndex) }}
                            </div>
                            <button
                                class="row-focus-btn"
                                @click="toggleRowFocus(rowIndex)"
                                :class="{ active: focusedRow === rowIndex }"
                            >
                                {{
                                    focusedRow === rowIndex
                                        ? 'Close'
                                        : 'View Orgs'
                                }}
                            </button>
                            <div class="path-line"></div>
                        </div>

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
                                <div
                                    v-if="focusedRow === rowIndex"
                                    class="stall-banner"
                                >
                                    <img
                                        :src="org.banner_url || fallbackBanner"
                                    />
                                </div>
                                <div class="stall-name">{{ org.org_name }}</div>
                                <span
                                    class="stall-cat"
                                    :style="{ color: getColor(org.category) }"
                                >
                                    {{ org.category }}
                                </span>

                                <div
                                    v-if="focusedRow === rowIndex"
                                    class="stall-desc"
                                >
                                    {{ org.description }}
                                </div>

                                <button
                                    v-if="focusedRow === rowIndex"
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
const userMemberships = ref([102, 105]);

const fallbackBanner =
    'https://up.edu.ph/wp-content/uploads/2024/05/UP-Mindanao-by-Jonathan-Madrid-2048x1024.jpg';

onMounted(async () => {
    try {
        const res = await get('/organizations');
        orgs.value = res.data ?? res;
    } catch (err) {
        console.error('API Error:', err);
    }
});

const toggleRowFocus = (index) => {
    focusedRow.value = focusedRow.value === index ? null : index;
};

const isMember = (orgId) => userMemberships.value.includes(orgId);

const handleApply = (orgId) => {
    alert(`Application sent to Org ID: ${orgId}`);
};

const categories = computed(() => [
    ...new Set(orgs.value.map((o) => o.category).filter(Boolean)),
]);

const palette = [
    '#EA7317',
    '#FEC601',
    '#9CD08F',
    '#3DA5D9',
    '#5941A9',
    '#ef4444',
    '#0ea5e9',
];
const getColor = (cat) => {
    if (!cat) return '#7f1d1d';
    const index = categories.value.indexOf(cat);
    return palette[index % palette.length] || '#7f1d1d';
};

const filteredOrgs = computed(() => {
    return orgs.value.filter((org) => {
        const matchesSearch =
            !search.value ||
            org.org_name?.toLowerCase().includes(search.value.toLowerCase());
        const matchesCat =
            !selectedCategory.value || org.category === selectedCategory.value;
        return matchesSearch && matchesCat;
    });
});

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
/* HEADER */
.header {
    margin-bottom: 18px;
}
h1 {
    margin: 0;
    font-size: 26px;
    font-weight: 900;
    color: #7f1d1d;
}
.subtext {
    margin-top: 4px;
    color: #6b7280;
    font-size: 14px;
}

/* MODAL STYLES */
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
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}
.close-modal {
    position: absolute;
    top: 15px;
    right: 15px;
    background: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-weight: bold;
    z-index: 10;
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
    margin: 0 0 8px 0;
    color: #1e293b;
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
.apply-btn {
    width: 100%;
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}
.apply-btn:hover {
    background: #064e3b;
    color: gold;
    border: 2px solid gold;
}
.member-status {
    width: 100%;
    padding: 12px;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    border-radius: 10px;
    font-weight: 700;
}

/* MAP PAGE LAYOUT */
.map-page {
    background: #f8fafc;
    padding: 24px;
    min-height: 100vh;
}
.topbar {
    display: flex;
    gap: 16px;
    align-items: center;
    margin-bottom: 24px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
    outline: none;
}

.layout {
    display: flex;
    gap: 24px;
    align-items: flex-start;
}

/* UPDATED SIDEBAR CSS */
.sidebar {
    width: 220px; /* Reduced width for a cleaner column */
    background: white;
    padding: 20px;
    border-radius: 16px;
    position: sticky;
    top: 24px;
    flex-shrink: 0; /* Prevents sidebar from squashing */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.cat-list {
    display: flex;
    flex-direction: column; /* Force vertical stacking */
    gap: 8px;
}
.sidebar h3 {
    font-size: 11px;
    color: #64748b;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
}

.cat-btn {
    width: 100%;
    text-align: left;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
    cursor: pointer;
    transition: 0.2s;
    font-size: 13px;
    font-weight: 500;
}

/* MAP CONTAINER */
.map-container {
    flex: 1;
    background: white;
    padding: 32px;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow-x: auto;
}

.walkway {
    display: flex;
    flex-direction: column;
    gap: 32px;
    min-width: 900px;
    padding-bottom: 10px;
}

/* STALLS AND LANES */
.lane {
    display: flex;
    align-items: center;
    gap: 20px;
    transition: 0.4s;
    justify-content: center;
}
.lane.dimmed {
    opacity: 0.25;
    filter: grayscale(0.5);
}
.lane.focused {
    transform: scale(1.02);
}
.lane-side {
    display: flex;
    gap: 14px;
    width: 380px;
}
.lane-side.left {
    justify-content: flex-end;
}
.lane-side.right {
    justify-content: flex-start;
}
.stall {
    width: 175px;
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}
.stall-banner img {
    width: 100%;
    height: 85px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 12px;
}
.stall-name {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
    min-height: 2.4em;
}
.stall-cat {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 14px;
    display: block;
}
.stall-desc {
    font-size: 11px;
    color: #64748b;
    line-height: 1.4;
    margin-bottom: 14px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.stall-btn {
    text-align: center;
    padding: 8px;
    border: 1.5px solid #e2e8f0;
    color: #475569;
    background: white;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.path {
    width: 110px;
    text-align: center;
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
    transition: 0.2s;
    white-space: nowrap;
    margin-bottom: 8px;
}
.row-focus-btn.active {
    background: #1e293b;
    color: white;
    border-color: #1e293b;
}
.path-line {
    width: 2px;
    height: 90px;
    background: repeating-linear-gradient(
        to bottom,
        #cbd5e1,
        #cbd5e1 6px,
        transparent 6px,
        transparent 12px
    );
    margin: 0 auto;
}
</style>
