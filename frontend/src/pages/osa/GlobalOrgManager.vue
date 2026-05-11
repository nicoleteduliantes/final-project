<template>
    <div class="page">
        <h2>Organization Management</h2>

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
                    <tr v-for="org in organizations" :key="org.org_id">
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
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get, post } from '@/services/apiService';

const organizations = ref([]);

const fetchData = async () => {
    try {
        const res = await get('/organizations');
        organizations.value = res.data ?? res;
    } catch (error) {
        console.error('Error fetching organizations:', error);
    }
};

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
    padding: 20px;
    padding-top: 0;
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

/* STATUS TAGS */
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

/* BUTTONS */
.btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    font-weight: 600;
    transition: all 0.2s ease;
}

/* Default state: Gray/Disabled (for Registered orgs) */
.reactivate {
    background: #e5e7eb;
    color: #9ca3af;
    cursor: not-allowed;
}

/* Clickable state: Green (for Expired orgs) */
.reactivate.active {
    background: #16a34a;
    color: white;
    cursor: pointer;
}

.reactivate.active:hover {
    background: #15803d;
}
</style>
