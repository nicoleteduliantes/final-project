<template>
    <Transition name="fade">
        <div v-if="modelValue" class="modal-overlay" @click.self="close">
            <div class="resume-modal">
                <!-- SIDEBAR -->
                <aside class="modal-sidebar">
                    <div class="avatar-large">
                        {{ getInitials(app?.student_name) }}
                    </div>

                    <h3 class="modal-name">
                        {{ app?.student_name || 'Unknown' }}
                    </h3>

                    <p class="modal-meta">
                        {{ app?.degree_program || 'N/A' }}
                    </p>

                    <div class="sidebar-stats">
                        <div class="stat">
                            <span class="label">Current Status</span>
                            <span :class="['status-pill', app?.status]">
                                {{ app?.status || 'Pending' }}
                            </span>
                        </div>
                    </div>

                    <div class="sidebar-actions">
                        <button
                            class="btn approve"
                            @click="update('Accepted', 'Member')"
                        >
                            Accept Applicant
                        </button>

                        <button
                            class="btn reject"
                            @click="update('Rejected', 'Applicant')"
                        >
                            Reject Applicant
                        </button>

                        <button class="btn close-text" @click="close">
                            Go Back
                        </button>
                    </div>
                </aside>

                <!-- BODY -->
                <main class="modal-body">
                    <header class="doc-header">
                        <h4>Review Application</h4>
                        <p>Applied for {{ app?.applied_committee || 'N/A' }}</p>
                    </header>

                    <!-- COVER LETTER -->
                    <section class="doc-section">
                        <h5 class="section-title">Cover Letter</h5>

                        <div class="doc-paper">
                            {{
                                app?.cover_letter || 'No cover letter provided'
                            }}
                        </div>
                    </section>

                    <!-- SKILLS (RAW) -->
                    <section class="doc-section">
                        <h5 class="section-title">Skills</h5>

                        <div class="doc-paper">
                            {{ app?.skills || 'No skills listed' }}
                        </div>
                    </section>

                    <!-- EXPERIENCE (RAW FIX) -->
                    <section class="doc-section">
                        <h5 class="section-title">Previous Experience</h5>

                        <div class="doc-paper">
                            {{
                                app?.previous_experience ||
                                'No experience provided'
                            }}
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </Transition>
</template>

<script setup>
const props = defineProps({
    modelValue: Boolean,
    app: Object,
});

const emit = defineEmits(['close', 'update']);

const close = () => emit('close');

const update = (status, position) => {
    emit('update', status, position);
};

const getInitials = (name = '') =>
    name
        .split(' ')
        .filter(Boolean)
        .map((n) => n[0])
        .join('')
        .toUpperCase();
</script>

<style scoped>
.status-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;

    padding: 6px 12px;
    border-radius: 999px;

    white-space: nowrap;
    min-width: 90px;
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
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.stat .label {
    display: block;
    font-size: 15px;
    margin-bottom: 4px;
    text-align: center;
}

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

.btn.approve:hover {
    background: #166534;
    color: white;
}

.btn.approve {
    background: white;
    color: #166534;
    border: 3px solid #166534;
}

.btn.reject:hover {
    background: #991b1b;
    color: white;
}

.btn.reject {
    background: white;
    color: #991b1b;
    border: 3px solid #991b1b;
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
    font-size: 15px;
    line-height: 1.6;
    color: #374151;
    white-space: pre-wrap;
}

.section-title {
    font-size: 12px;
    color: #9ca3af;
    text-transform: uppercase;
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

.skill-tag.empty {
    opacity: 0.6;
}

/* ANIMATION */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
