<template>
    <div class="page-container">
        <header class="page-header">
            <div class="title-group">
                <h2 class="title">Review Applications</h2>
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
                        <span :class="['status-pill', app.status]">
                            {{ app.status }}
                        </span>
                    </div>
                </div>

                <div class="sheet-content">
                    <div class="info-bit">
                        <span class="label">Committee</span>
                        <span class="value">{{ app.applied_committee }}</span>
                    </div>
                    <div class="info-bit letter-preview">
                        <span class="label">Cover Letter Preview</span>
                        <p class="letter-text">{{ app.cover_letter }}</p>
                    </div>
                </div>

                <div class="sheet-footer">
                    <span class="action-hint">View Full Application →</span>
                </div>
            </div>
        </div>

        <Transition name="fade">
            <div
                v-if="selectedApp"
                class="modal-overlay"
                @click.self="selectedApp = null"
            >
                <div class="resume-modal">
                    <aside class="modal-sidebar">
                        <div class="avatar-large">
                            {{ getInitials(selectedApp.student_name) }}
                        </div>
                        <h3 class="modal-name">
                            {{ selectedApp.student_name }}
                        </h3>
                        <p class="modal-meta">
                            {{ selectedApp.degree_program }}
                        </p>

                        <div class="sidebar-stats">
                            <div class="stat">
                                <span class="label">Current Status</span>
                                <span
                                    :class="[
                                        'status-pill',
                                        selectedApp?.status ?? 'Pending',
                                    ]"
                                >
                                    {{ selectedApp?.status ?? 'Pending' }}
                                </span>
                            </div>
                        </div>

                        <div class="sidebar-actions">
                            <button
                                class="btn approve"
                                @click="handleUpdate('Accepted', 'Member')"
                            >
                                Accept Applicant
                            </button>
                            <button
                                class="btn reject"
                                @click="handleUpdate('Rejected', 'Applicant')"
                            >
                                Reject Applicant
                            </button>
                            <button
                                class="btn close-text"
                                @click="selectedApp = null"
                            >
                                Go Back
                            </button>
                        </div>
                    </aside>

                    <main class="modal-body">
                        <header class="doc-header">
                            <h4>Review Application</h4>
                            <p>
                                Applied for
                                {{ selectedApp?.applied_committee || 'N/A' }}
                                Committee
                            </p>
                        </header>

                        <section class="doc-section">
                            <h5 class="section-title">Cover Letter</h5>
                            <div class="doc-paper letter-body">
                                {{ selectedApp.cover_letter }}
                            </div>
                        </section>

                        <section class="doc-section">
                            <h5 class="section-title">
                                Technical Skills & Expertise
                            </h5>
                            <div class="skills-flex">
                                <template
                                    v-if="
                                        selectedApp.raw.application_detail
                                            ?.skills
                                    "
                                >
                                    <span
                                        v-for="skill in selectedApp.raw.application_detail.skills.split(
                                            ','
                                        )"
                                        :key="skill"
                                        class="skill-tag"
                                    >
                                        {{ skill.trim() }}
                                    </span>
                                </template>
                                <span v-else class="empty-msg"
                                    >No specific skills listed.</span
                                >
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </Transition>
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

            college:
                item.student.degree_program?.college?.college_name || 'N/A',

            applied_committee:
                item.application_detail?.applied_committee || 'N/A',

            cover_letter:
                item.application_detail?.cover_letter ||
                'No cover letter provided',

            status: item.status ?? 'Pending',

            raw: item,
        }));
    } catch (error) {
        console.error('Fetch error:', error);
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
    selectedApp.value = app;
};

/* UPDATE */

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

/* INITIALS */

const getInitials = (name = '') => {
    return name

        .split(' ')

        .filter(Boolean)

        .map((n) => n[0])

        .join('')

        .toUpperCase();
};

onMounted(fetchApplications);
</script>

<style scoped>
/* GENERAL LAYOUT */
.page-container {
    padding-left: 60px;
    padding-top: 20px;
    max-width: 1200px;
    margin: 0 auto;
    font-family: 'Inter', system-ui, sans-serif;
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

/* 1. Ensure the base pill style is robust */
.status-pill {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 999px; /* Rounded pill shape */
    white-space: nowrap;
}

/* 2. Color logic (shared by both grid and modal) */
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

/* 3. Modal specific positioning fix */
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
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
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

/* MODAL MODERNIZATION */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(17, 24, 39, 0.7);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 100;
}

.resume-modal {
    width: 95%;
    max-width: 900px;
    height: 85vh;
    background: #f9fafb;
    border-radius: 24px;
    display: flex;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.modal-sidebar {
    width: 280px;
    background: white;
    border-right: 1px solid #e5e7eb;
    padding: 32px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.avatar-large {
    width: 80px;
    height: 80px;
    background: #7f1d1d;
    color: white;
    border-radius: 20px;
    font-size: 24px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.modal-name {
    font-size: 20px;
    font-weight: 800;
    text-align: center;
    margin-bottom: 4px;
}
.modal-meta {
    font-size: 13px;
    color: #6b7280;
    text-align: center;
    margin-bottom: 24px;
}

.sidebar-actions {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: auto;
}
.btn {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    border: none;
    transition: 0.2s;
}
.btn.approve {
    background: #166534;
    color: white;
}
.btn.reject {
    background: #991b1b;
    color: white;
}
.btn.close-text {
    background: transparent;
    color: #6b7280;
}
.btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
}

.modal-body {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
    background: #f3f4f6;
}
.doc-header {
    margin-bottom: 32px;
}
.doc-header h4 {
    font-size: 22px;
    margin: 0;
}
.doc-header p {
    color: #6b7280;
    margin: 4px 0;
}

.doc-paper {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    font-size: 15px;
    line-height: 1.6;
    color: #374151;
    white-space: pre-wrap;
}

.section-title {
    font-size: 12px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}
.doc-section {
    margin-bottom: 32px;
}

.skills-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.skill-tag {
    background: #e5e7eb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #4b5563;
}

/* ANIMATIONS */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
