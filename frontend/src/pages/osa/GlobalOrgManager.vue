<template>
    <div class="page">
        <h2>List of Organizations</h2>

        <!-- FILTER BAR -->
        <div class="filters">
            <input v-model="search" placeholder="Search organizations..." />

            <select v-model="selectedStatus">
                <option value="">All Status</option>
                <option v-for="s in statuses" :key="s" :value="s">
                    {{ s }}
                </option>
            </select>
        </div>

        <!-- GRID -->
        <div class="grid">
            <div
                class="card"
                v-for="org in filteredOrganizations"
                :key="org.id"
            >
                <div class="card-body">
                    <h3>{{ org.org_name }}</h3>

                    <p class="college">
                        {{ org.college ?? 'N/A' }}
                    </p>

                    <span :class="['status', org.status]">
                        {{ org.status }}
                    </span>

                    <!-- ACTIONS -->
                    <div class="actions">
                        <button
                            v-if="org.status === 'Registered'"
                            class="btn expire"
                            @click="updateStatus(org.id, 'Expired')"
                        >
                            Mark Expired
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
                </div>
            </div>

            <!-- EMPTY -->
            <div v-if="filteredOrganizations.length === 0" class="empty">
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

/* ONLY THESE STATUSES */
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

/* FILTER LOGIC */
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
/* PAGE */
.page {
    padding: 20px;
    padding-top: 0px;
}

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

.card-body {
    padding: 14px;
}

/* TEXT */
h3 {
    margin: 0;
    font-size: 16px;
}

.college {
    font-size: 13px;
    color: #555;
    margin: 6px 0;
}

/* STATUS */
.status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 12px;
    text-transform: capitalize;
    margin: 6px 0;
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
    margin-top: 10px;
}

/* BUTTONS */
.btn {
    flex: 1;
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid transparent;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s ease;
}

/* EXPIRE */
.expire {
    background: #dc2626;
    color: white;
}

.expire:hover {
    background: #b91c1c;
}

/* REACTIVATE */
.reactivate {
    background: #16a34a;
    color: white;
}

.reactivate:hover {
    background: #15803d;
}

/* BADGE */
.badge {
    flex: 1;
    text-align: center;
    font-size: 12px;
    background: #e5e7eb;
    padding: 6px 8px;
    border-radius: 6px;
}

/* EMPTY */
.empty {
    grid-column: 1 / -1;
    text-align: center;
    color: #6b7280;
    padding: 20px;
}
</style>
