<template>
    <div class="page">
        <div class="header">
            <h1>Manage Members</h1>
            <p class="subtext">Organization Members</p>
        </div>

        <!-- EMPTY STATE -->
        <div v-if="members.length === 0" class="empty-msg">
            No membership records found.
        </div>

        <!-- BOOTH GRID -->
        <div v-else class="booth-grid">
            <div class="booth-card" v-for="m in members" :key="m.membership_id">
                <!-- TOP CANOPY -->
                <div class="booth-top">
                    <span class="org">MEMBER</span>

                    <span class="badge">
                        {{ m.assigned_committee }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="booth-body">
                    <div class="avatar">
                        {{ getInitials(m.student_name) }}
                    </div>

                    <div class="info">
                        <p class="label">Name</p>
                        <p class="value">{{ m.student_name }}</p>

                        <p class="label">Email</p>
                        <p class="value">{{ m.email }}</p>

                        <p class="label">Valid Until</p>
                        <p class="value">{{ m.valid_until }}</p>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="booth-footer">
                    <button
                        class="danger"
                        @click="removeMember(m.membership_id)"
                    >
                        Remove Member
                    </button>

                    <span class="id">ID NO. {{ m.membership_id }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get, del } from '@/services/apiService';

const members = ref([]);

const fetchMembers = async () => {
    try {
        const response = await get('/org/members');

        members.value = response.map((m) => {
            const joinedDate = new Date();
            const expiryDate = new Date();
            expiryDate.setFullYear(joinedDate.getFullYear() + 1);

            return {
                membership_id: m.membership_id,
                student_name: `${m.student.first_name} ${m.student.last_name}`,
                email: m.student.up_email,
                assigned_committee: m.assigned_committee || 'General',
                valid_until: expiryDate.toLocaleDateString(),
            };
        });
    } catch (error) {
        console.error('Error loading members:', error);
    }
};

const removeMember = async (id) => {
    if (confirm('Are you sure you want to revoke this member?')) {
        try {
            await del(`/org/members/${id}`);
            await fetchMembers();
        } catch (error) {
            alert('Failed to remove member.');
        }
    }
};

const getInitials = (name) => {
    if (!name) return '';
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase();
};

onMounted(fetchMembers);
</script>

<style scoped>
/* PAGE */
.page {
    padding-left: 20px;
    padding-top: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* HEADER */
.header {
    margin-bottom: 18px;
}

.subtext {
    margin-top: 4px;
    color: #6b7280;
    font-size: 14px;
}

/* EMPTY */
.empty-msg {
    padding: 20px;
    text-align: center;
    color: #6b7280;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
}

/* GRID */
.booth-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 28px;
    padding: 20px;
}

/* CARD */
.booth-card {
    position: relative;
    transition: 0.3s ease;
    transform: rotate(-0.4deg);
}

.booth-card:nth-child(even) {
    transform: rotate(0.5deg);
}

.booth-card:hover {
    transform: translateY(-10px) rotate(0deg);
}

/* TOP */
.booth-top {
    padding: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;

    color: white;
    background: linear-gradient(135deg, #7f1d1d, #b91c1c);
    border-radius: 14px 14px 0 0;

    box-shadow:
        0 10px 0 rgba(0, 0, 0, 0.12),
        0 18px 30px rgba(0, 0, 0, 0.15);
}

.org {
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
}

/* BADGE */
.badge {
    font-size: 10px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 999px;
    background: #e5e7eb;
    color: #111827;
}

/* BODY */
.booth-body {
    display: flex;
    gap: 14px;
    align-items: center;
    padding: 16px;
    background: white;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
}

/* AVATAR */
.avatar {
    width: 58px;
    height: 58px;
    border-radius: 14px;
    background: radial-gradient(circle at top left, #7f1d1d, #991b1b);
    color: white;

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: 900;

    box-shadow:
        inset 0 0 0 2px rgba(255, 255, 255, 0.2),
        0 6px 12px rgba(0, 0, 0, 0.15);
}

/* INFO */
.info {
    flex: 1;
}

.label {
    font-size: 10px;
    color: #a8a29e;
    text-transform: uppercase;
    margin-top: 6px;
    letter-spacing: 0.5px;
}

.value {
    font-size: 13px;
    font-weight: 700;
    color: #1c1917;
}

/* FOOTER */
.booth-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 10px 14px;
    font-size: 11px;

    background: linear-gradient(90deg, #374151, #1f2937);
    border-radius: 0 0 14px 14px;

    box-shadow:
        0 12px 0 rgba(0, 0, 0, 0.15),
        0 18px 30px rgba(0, 0, 0, 0.12);
}

/* ID */
.id {
    color: #f9fafb;
    font-size: 10px;
}

/* BUTTON */
.danger {
    background: transparent;
    border: 1px solid #fca5a5;
    color: #fca5a5;

    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;

    cursor: pointer;
    transition: 0.2s ease;
}

.danger:hover {
    background: #7f1d1d;
    border-color: #7f1d1d;
    color: white;
}
</style>
