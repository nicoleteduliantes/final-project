<template>
    <div class="page">
        <header class="page-header">
            <div class="title-group">
                <h1>Review Applications</h1>
                <p class="count">
                    {{ filteredApplications.length }} applicants found
                </p>
            </div>

            <div class="filter-chips">
                <button
                    v-for="status in ['', 'Pending', 'Accepted', 'Rejected']"
                    :key="status"
                    :class="['chip', { active: selectedStatus === status }]"
                    @click="selectedStatus = status"
                >
                    {{ status || 'All' }}
                </button>
            </div>
        </header>

        <!-- GRID -->
        <div class="resume-grid">
            <div
                class="resume-sheet"
                v-for="app in filteredApplications"
                :key="app.membership_id"
                @click="openReview(app)"
            >
                <div class="sheet-top">
                    <div class="identity-row">
                        <div class="avatar-small">
                            {{ getInitials(app.student_name) }}
                        </div>

                        <div class="name-box">
                            <h3 class="name">{{ app.student_name }}</h3>
                            <p class="meta">{{ app.college }}</p>
                        </div>

                        <span class="status-pill" :class="app?.status">
                            {{ app?.status || 'Pending' }}
                        </span>
                    </div>
                </div>

                <div class="sheet-content">
                    <div class="info-bit">
                        <span class="label">Committee</span>
                        <span class="value">{{ app.applied_committee }}</span>
                    </div>

                    <div class="info-bit letter-preview">
                        <span class="label">Cover Letter</span>
                        <p class="letter-text">
                            {{ app.cover_letter }}
                        </p>
                    </div>
                </div>

                <div class="sheet-footer">
                    <span class="action-hint"> View Full Application → </span>
                </div>
            </div>
        </div>

        <!-- MODAL COMPONENT -->
        <ApplicationReviewModal
            :model-value="!!selectedApp"
            :app="selectedApp"
            @close="selectedApp = null"
            @update="handleUpdate"
        />

        <button class="red-pill-button" @click="toggleApplicationStatus">
            {{
                orgApplicationStatus === 'open'
                    ? 'Close Applications'
                    : 'Open Applications'
            }}
        </button>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get, put } from '@/services/apiService';
import ApplicationReviewModal from '@/components/ApplicationReviewModal.vue';

/* STATE */
const applications = ref([]);
const selectedApp = ref(null);
const selectedStatus = ref('');
const orgApplicationStatus = ref('closed');

/* FETCH */
const fetchApplications = async () => {
    try {
        const orgResponse = await get('/org/profile');
        orgApplicationStatus.value = orgResponse.application_status;

        const response = await get('/org/applications');

        applications.value = response.map((item) => ({
            membership_id: item.membership_id,

            student_name: `${item.student.first_name} ${item.student.last_name}`,

            degree_program: item.student.degree_program?.degprog_name || 'N/A',

            college:
                item.student.degree_program?.college?.college_name || 'N/A',

            applied_committee:
                item.application_detail?.applied_committee || 'N/A',

            cover_letter:
                item.application_detail?.cover_letter ||
                'No cover letter provided',

            skills: item.application_detail?.skills || 'No skills listed',

            previous_experience:
                item.application_detail?.previous_experience ||
                'No experience provided',

            status: item.status ?? 'Pending',

            raw: item,
        }));
    } catch (error) {
        console.error('Fetch error:', error);
    }
};

const toggleApplicationStatus = async () => {
    const newStatus = orgApplicationStatus.value === 'open' ? 'closed' : 'open';
    const confirmMsg = `Are you sure you want to ${newStatus.toUpperCase()} applications?`;

    if (!confirm(confirmMsg)) return;

    try {
        await put('/org/status', { application_status: newStatus });
        orgApplicationStatus.value = newStatus;
        alert(`Applications are now ${newStatus}.`);
    } catch (error) {
        alert('Failed to update status.');
        console.error(error);
    }
};

/* FILTER */
const filteredApplications = computed(() => {
    if (!selectedStatus.value) return applications.value;

    return applications.value.filter(
        (app) => app.status === selectedStatus.value
    );
});

/* OPEN MODAL */
const openReview = (app) => {
    console.log('SELECTED APP:', app);
    selectedApp.value = app;
};

/* UPDATE */
const handleUpdate = async (status, position) => {
    const id = selectedApp.value.membership_id;

    const actionText = status === 'Accepted' ? 'approve' : 'reject';

    if (!confirm(`Are you sure you want to ${actionText} this applicant?`))
        return;

    try {
        await put(`/org/applications/${id}`, {
            status,
            position,
        });

        selectedApp.value = null;
        await fetchApplications();
    } catch (error) {
        alert('Update failed.');
        console.error(error);
    }
};

/* INITIALS */
const getInitials = (name = '') =>
    name
        .split(' ')
        .filter(Boolean)
        .map((n) => n[0])
        .join('')
        .toUpperCase();

onMounted(fetchApplications);
</script>

<style scoped>
.page {
    padding: clamp(20px, 10vw, 50px);
    min-height: 100vh;
    width: 100%;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 32px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 16px;
}

.title {
    font-size: 24px;
    font-weight: 800;
    color: #7f1d1d;
    margin: 0;
}
.count {
    color: #6b7280;
    font-size: 14px;
    margin: 4px 0 0 0;
}

/* FILTERS */
.filter-chips {
    display: flex;
    gap: 8px;
}
.chip {
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    background: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    color: #4b5563;
}
.chip.active {
    background: #7f1d1d;
    color: white;
    border-color: #7f1d1d;
}

/* GRID & CARDS */
.resume-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

.resume-sheet {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
}

.resume-sheet:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
    border-color: #7f1d1d44;
}

.identity-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    position: relative;
}
.avatar-small {
    width: 44px;
    height: 44px;
    background: #f3f4f6;
    color: #7f1d1d;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 1px solid #e5e7eb;
}

.name {
    font-size: 16px;
    font-weight: 700;
    margin: 0;
    color: #1f2937;
}
.meta {
    font-size: 12px;
    color: #6b7280;
    margin: 2px 0 0 0;
}

.status-pill {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 999px;
    white-space: nowrap;
}

.status-pill.Pending {
    background: #fef3c7;
    color: #92400e;
}

.status-pill.Accepted {
    background: #dcfce7;
    color: #166534;
}

.status-pill.Rejected {
    background: #fee2e2;
    color: #991b1b;
}

.sidebar-stats {
    width: 100%;
    margin-top: 10px;
    padding: 15px;
    background: #f3f4f6;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.stat .label {
    display: block;
    font-size: 10px;
    color: #9ca3af;
    margin-bottom: 4px;
    text-align: center;
}

.info-bit {
    margin-bottom: 12px;
}
.label {
    font-size: 11px;
    text-transform: uppercase;
    color: #9ca3af;
    font-weight: 600;
    display: block;
    margin-bottom: 4px;
}
.value {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.letter-text {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;

    display: -webkit-box;
    -webkit-box-orient: vertical;

    -webkit-line-clamp: 2;
    line-clamp: 2;

    overflow: hidden;
}

.sheet-footer {
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px dashed #e5e7eb;
}
.action-hint {
    font-size: 12px;
    font-weight: 700;
    color: #7f1d1d;
}

.red-pill-button {
    position: fixed;
    bottom: 40px;
    right: 40px;
    z-index: 1000;

    background-color: #7f1d1d;
    color: white;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 14px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;

    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.red-pill-button:hover {
    background-color: #064e3b;
    color: #facc15;
    border-color: #facc15;
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
}

.red-pill-button:active {
    transform: translateY(-2px);
}
</style>
