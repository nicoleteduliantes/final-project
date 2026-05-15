<template>
    <div class="page">
        <div class="header-row">
            <h1>Global Organization Manager</h1>
            <div class="controls">
                <select v-model="statusFilter" class="status-select">
                    <option value="">All Statuses</option>
                    <option value="Registered">Registered</option>
                    <option value="Expired">Expired</option>
                </select>
                <div class="search-box">
                    <input
                        v-model="search"
                        placeholder="Search organizations..."
                    />
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Organization Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="org in filteredOrgs" :key="org.org_id">
                        <td class="name-column">{{ org.org_name }}</td>
                        <td>
                            <span :class="['status', org.status]">
                                {{ org.status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <button
                                    class="btn reactivate"
                                    :class="{
                                        active: org.status === 'Expired',
                                    }"
                                    :disabled="org.status === 'Registered'"
                                    @click="reactivateOrg(org)"
                                >
                                    Reactivate
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="filteredOrgs.length === 0" class="empty-msg">
                No organizations found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get, post } from '@/services/apiService';

const organizations = ref([]);
const search = ref('');
const statusFilter = ref('');

const fetchData = async () => {
    try {
        const res = await get('/organizations');
        organizations.value = res.data ?? res;
    } catch (error) {
        console.error('Error fetching organizations:', error);
    }
};

/* FILTER */
const filteredOrgs = computed(() => {
    return organizations.value.filter((org) => {
        const orgName = org.org_name.toLowerCase();
        const query = search.value.toLowerCase();

        const matchesSearch = orgName.includes(query);
        const matchesStatus =
            !statusFilter.value || org.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

const reactivateOrg = async (org) => {
    try {
        await post(`/organizations/reactivate/${org.org_id}`);
        org.status = 'Registered';
        alert(`${org.org_name} has been reactivated.`);
    } catch (error) {
        console.error('Laravel Error:', error.response?.data || error.message);
        alert('Failed to reactivate. Check console for details.');
    }
};

onMounted(fetchData);
</script>

<style scoped>
.page {
    padding: clamp(20px, 10vw, 50px);
    min-height: 100vh;
    width: 100%;
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.controls {
    display: flex;
    gap: 10px;
}

.search-box input,
.status-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.search-box input {
    width: 200px;
}

.empty-msg {
    text-align: center;
    padding: 30px;
    color: #6b7280;
}

.table-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px 15px;
    text-align: left;
    font-size: 14px;
}

thead {
    background: #f3f4f6;
}

tbody tr {
    border-top: 1px solid #eee;
}

.name-column {
    font-weight: 600;
}

.status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 12px;
}

.status.Registered {
    background: #dcfce7;
    color: #166534;
}

.status.Expired {
    background: #fee2e2;
    color: #991b1b;
}

.btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

.reactivate {
    background: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
}

.reactivate.active {
    background: #16a34a;
    color: white;
    cursor: pointer;
}

.reactivate.active:hover {
    background: #15803d;
}
</style>
