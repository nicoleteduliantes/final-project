<template>
    <div class="page">
        <h2 class="title">Review Applications</h2>

        <!-- FILTER -->
        <div class="filters">
            <select v-model="selectedStatus">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Accepted">Accepted</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Committee</th>
                        <th>Cover Letter</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="app in filteredApplications"
                        :key="app.membership_id"
                    >
                        <td class="name">{{ app.student_name }}</td>

                        <td>
                            <span class="tag">{{ app.applied_committee }}</span>
                        </td>

                        <td class="clamp">{{ app.cover_letter }}</td>

                        <td>
                            <span :class="['status', app.status]">
                                {{ app.status }}
                            </span>
                        </td>

                        <td>
                            <button class="btn view" @click="openReview(app)">
                                View Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- MODAL -->
        <div v-if="selectedApp" class="modal-overlay">
            <div class="modal">
                <h3>Application Review</h3>
                <p class="subtitle">{{ selectedApp.student_name }}</p>
                <p class="academic-subtitle">
                {{ selectedApp.degree_program }} | {{ selectedApp.college }}
                </p>

                <div class="modal-body">
                    <p>
                        <strong>Applied Committee:</strong>
                        {{ selectedApp.applied_committee }}
                    </p>
                    
                    <p><strong>Cover Letter:</strong></p>
                    <div class="box">{{ selectedApp.cover_letter }}</div>

                    <p>
                        <strong>Skills:</strong>
                        {{
                            selectedApp.raw.application_detail?.skills ||
                            'None listed'
                        }}
                    </p>
                </div>

                <div class="modal-actions">
                    <button
                        class="btn approve"
                        @click="handleUpdate('Accepted', 'Member')"
                    >
                        Approve
                    </button>

                    <button
                        class="btn reject"
                        @click="handleUpdate('Rejected', 'Applicant')"
                    >
                        Reject
                    </button>

                    <button class="btn close" @click="selectedApp = null">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { get, put } from '@/services/apiService';

const applications = ref([]);
const selectedApp = ref(null);
const selectedStatus = ref('');

/* FETCH DATA */
const fetchApplications = async () => {
    try {
        const response = await get('/org/applications');

        applications.value = response.map((item) => ({
            membership_id: item.membership_id,
            student_name: `${item.student.first_name} ${item.student.last_name}`,

            degree_program: item.student.degree_program?.degprog_name || 'N/A',
            college: item.student.degree_program?.college?.college_name || 'N/A',

            applied_committee:
                item.application_detail?.applied_committee || 'N/A',
            cover_letter:
                item.application_detail?.cover_letter ||
                'No cover letter provided',
            status: item.status || 'Pending',
            raw: item,
        }));
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

/* FILTERED LIST */
const filteredApplications = computed(() => {
    if (!selectedStatus.value) {
        return applications.value;
    }

    return applications.value.filter(
        (app) => app.status === selectedStatus.value
    );
});

/* MODAL */
const openReview = (app) => {
    selectedApp.value = app;
};

/* UPDATE STATUS */
const handleUpdate = async (status, position) => {
    const id = selectedApp.value.membership_id;

    const actionText = status === 'Accepted' ? 'approve' : 'reject';

    if (!confirm(`Are you sure you want to ${actionText} this applicant?`))
        return;

    try {
        await put(`/org/applications/${id}`, { status, position });
        selectedApp.value = null;
        await fetchApplications();
    } catch (error) {
        alert('Update failed.');
    }
};

onMounted(fetchApplications);
</script>

<style scoped>
.title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 12px;
}

.filters {
    margin-bottom: 12px;
}

.filters select {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

/* TABLE */
.table-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    padding: 12px;
    background: #f9fafb;
}

td {
    padding: 12px;
    border-top: 1px solid #eee;
}

.name {
    font-weight: 600;
}

.tag {
    background: #e5e7eb;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 12px;
}

.clamp {
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* STATUS */
.status {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status.Pending {
    background: #fef3c7;
    color: #92400e;
}

.status.Accepted {
    background: #dcfce7;
    color: #166534;
}

.status.Rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* BUTTONS */
.btn {
    padding: 6px 10px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
    font-weight: 600;
}

.view {
    background: #7f1d1d;
    color: white;
}
.approve {
    background: #16a34a;
    color: white;
}
.reject {
    background: #dc2626;
    color: white;
}
.close {
    background: #6b7280;
    color: white;
}

/* MODAL */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal {
    background: white;
    width: 500px;
    padding: 20px;
    border-radius: 12px;
}

.subtitle {
    color: #6b7280;
    margin-bottom: 0;
}

.academic-subtitle {
    font-size: 0.7rem;
    color: #6b7280;
    margin: 0;
    font-style: italic;
}
.box {
    background: #f3f4f6;
    padding: 10px;
    border-radius: 8px;
}

.modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
}
</style>
