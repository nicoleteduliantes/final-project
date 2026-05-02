<template>
    <div class="page">
        <h2>List of Organizations</h2>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Org Name</th>
                        <th>College</th>
                        <th>Status</th>
                        <th class="actions">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="org in organizations" :key="org.id">
                        <td>{{ org.org_name }}</td>
                        <td>{{ org.college ?? 'N/A' }}</td>

                        <td>
                            <span :class="['status', org.status]">
                                {{ org.status }}
                            </span>
                        </td>

                        <td class="actions">
                            <button
                                v-if="org.status === 'pending'"
                                class="approve"
                                @click="updateStatus(org.id, 'approved')"
                            >
                                Approve
                            </button>

                            <button
                                v-if="org.status === 'pending'"
                                class="reject"
                                @click="updateStatus(org.id, 'rejected')"
                            >
                                Reject
                            </button>
                        </td>
                    </tr>

                    <tr v-if="organizations.length === 0">
                        <td colspan="4" class="empty">
                            No organizations found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get } from '@/services/apiService';

const organizations = ref([]);

const fetchOrganizations = async () => {
    try {
        const res = await get('/osa/organizations');

        console.log('RAW RESPONSE:', res);

        organizations.value = res.data ?? res;
    } catch (err) {
        console.error('Fetch failed:', err);
    }
};

onMounted(() => {
    fetchOrganizations();
});
</script>

<style scoped>
.page {
    padding: 20px;
}

/* Header */
.header {
    margin-bottom: 15px;
}

.header h1 {
    font-size: 22px;
    font-weight: 600;
}

/* Card container */
.table-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f3f4f6;
}

th,
td {
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

tbody tr {
    border-top: 1px solid #eee;
}

/* Actions */
.actions {
    display: flex;
    gap: 8px;
}

/* Buttons */
button {
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
}

.approve {
    background: #16a34a;
    color: white;
}

.reject {
    background: #dc2626;
    color: white;
}

/* Status badges */
.status {
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 12px;
    text-transform: capitalize;
}

.status.pending {
    background: #fef3c7;
    color: #92400e;
}

.status.approved {
    background: #dcfce7;
    color: #166534;
}

.status.rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* Empty state */
.empty {
    text-align: center;
    padding: 20px;
    color: #6b7280;
}
</style>
