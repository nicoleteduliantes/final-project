<template>
    <div class="page">
        <div class="header-row">
            <h1>List of Organizations</h1>

            <div class="controls">
                <select v-model="selectedStatus" class="status-select">
                    <option value="">All Status</option>
                    <option v-for="s in statuses" :key="s" :value="s">
                        {{ s }}
                    </option>
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
                        <th>Organization</th>
                        <th>College</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="org in filteredOrganizations" :key="org.id">
                        <td class="name-column">
                            {{ org.org_name }}
                        </td>

                        <td>
                            <span class="college-tag">
                                {{ org.college ?? 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <span :class="['status', org.status]">
                                {{ org.status || 'N/A' }}
                            </span>
                        </td>

                        <td>
                            <div class="actions">
                                <button
                                    v-if="org.status === 'Registered'"
                                    class="btn expire"
                                    @click="updateStatus(org.id, 'Expired')"
                                >
                                    Expire
                                </button>

                                <button
                                    v-if="org.status === 'Expired'"
                                    class="btn reactivate"
                                    @click="updateStatus(org.id, 'Registered')"
                                >
                                    Reactivate
                                </button>

                                <span v-if="!org.status" class="badge">
                                    No actions
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="filteredOrganizations.length === 0" class="empty-msg">
                No organizations found
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get, post } from '@/services/apiService';

const organizations = ref([]);
const search = ref('');
const selectedStatus = ref('');

const statuses = ['Registered', 'Expired'];

const fetchOrganizations = async () => {
    try {
        const res = await get('/osa/organizations');
        organizations.value = res.data ?? res;
    } catch (err) {
        console.error('Fetch failed:', err);
    }
};

const updateStatus = async (id, status) => {
    try {
        await post(`/osa/organizations/${id}/status`, { status });

        const org = organizations.value.find((o) => o.id === id);
        if (org) org.status = status;
    } catch (err) {
        console.error('Update failed:', err);
    }
};

const filteredOrganizations = computed(() => {
    return organizations.value.filter((org) => {
        const matchesSearch =
            org.org_name?.toLowerCase().includes(search.value.toLowerCase()) ||
            org.college?.toLowerCase().includes(search.value.toLowerCase());

        const matchesStatus =
            !selectedStatus.value || org.status === selectedStatus.value;

        return matchesSearch && matchesStatus;
    });
});

onMounted(fetchOrganizations);
</script>

<style scoped>
.page {
    padding: 20px;
    padding-top: 0;
}

/* HEADER */
.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.header-row h1 {
    font-size: 22px;
    font-weight: 600;
}

.controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-box input,
.status-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

/* TABLE */
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

thead {
    background: #f3f4f6;
}

th,
td {
    padding: 12px 15px;
    text-align: left;
    font-size: 14px;
}

tbody tr {
    border-top: 1px solid #eee;
}

tbody tr:hover {
    background-color: #f9fafb;
}

.name-column {
    font-weight: 600;
    color: #111827;
}

/* STATUS */
.status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 12px;
    text-transform: capitalize;
}

.status.Registered {
    background: #dcfce7;
    color: #166534;
}

.status.Expired {
    background: #fee2e2;
    color: #991b1b;
}

/* ACTIONS */
.actions {
    display: flex;
    gap: 8px;
}

/* BUTTONS */
.btn {
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}

.expire {
    background: #dc2626;
    color: white;
}

.expire:hover {
    background: #b91c1c;
}

.reactivate {
    background: #16a34a;
    color: white;
}

.reactivate:hover {
    background: #15803d;
}

/* BADGE */
.badge {
    font-size: 12px;
    background: #e5e7eb;
    padding: 6px 8px;
    border-radius: 6px;
}

/* EMPTY */
.empty-msg {
    text-align: center;
    padding: 30px;
    color: #6b7280;
}
</style>
