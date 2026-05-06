<template>
    <div class="page">
        <h1>Memberships</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Organization</th>
                        <th>Position</th>
                        <th>Committee</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="membership in memberships"
                        :key="membership.membership_id"
                    >
                        <td class="org-column">
                            {{ membership.organization?.org_name }}
                        </td>
                        <td>{{ membership.position }}</td>
                        <td>
                            {{
                                membership.status.toLowerCase() === 'pending'
                                    ? membership.application_detail
                                          ?.applied_committee || 'N/A'
                                    : membership.assigned_committee ||
                                      'General Member'
                            }}
                        </td>
                        <td>
                            <span
                                :class="[
                                    'badge',
                                    membership.status.toLowerCase(),
                                ]"
                            >
                                {{ membership.status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="memberships.length === 0" class="empty-msg">
                No memberships records found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get } from '@/services/apiService';

const memberships = ref([]);

onMounted(async () => {
    try {
        // Calls the backend route
        const res = await get('/memberships');
        memberships.value = res;
    } catch (err) {
        console.error('Failed to load participation records', err);
    }
});
</script>

<style scoped>
.table-container {
    margin-top: 20px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

th {
    background-color: #7f1d1d; /* Matching your theme color */
    color: white;
    padding: 12px 16px;
    font-weight: 600;
}

td {
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    text-transform: uppercase;
}

.badge.active {
    background: #dcfce7;
    color: #166534;
}
.badge.expired {
    background: #fee2e2;
    color: #991b1b;
}

.link {
    color: #b45309;
    text-decoration: none;
    font-weight: 600;
}

.empty-msg {
    padding: 20px;
    text-align: center;
    color: #666;
}
</style>
