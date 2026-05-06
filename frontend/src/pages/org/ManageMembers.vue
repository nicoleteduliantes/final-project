<template>
    <div class="page">
        <h2>Manage Members</h2>

        <!-- MEMBER GRID -->
        <div class="grid">
            <div
                class="member-card"
                v-for="m in members"
                :key="m.membership_id"
            >
                <!-- AVATAR -->
                <div class="avatar">
                    {{ getInitials(m.student_name) }}
                </div>

                <!-- INFO -->
                <div class="info">
                    <h3>{{ m.student_name }}</h3>
                    <p class="email">{{ m.email }}</p>

                    <span class="badge">
                        {{ m.applied_committee }}
                    </span>
                </div>

                <!-- ACTION -->
                <button class="danger" @click="removeMember(m.membership_id)">
                    Remove
                </button>
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

        members.value = response.map((m) => ({
            membership_id: m.membership_id,
            student_name: `${m.student.first_name} ${m.student.last_name}`,
            email: m.student.up_email,
            applied_committee:
                m.application_detail?.applied_committee || 'General',
        }));
    } catch (error) {
        console.error('Error loading members:', error);
    }
};

const removeMember = async (id) => {
    if (confirm('Are you sure you want to remove this member?')) {
        try {
            await del(`/org/members/${id}`);
            await fetchMembers();
        } catch (error) {
            alert('Failed to remove member.');
        }
    }
};

/* initials for avatar */
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
/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

/* CARD */
.member-card {
    background: white;
    border-radius: 12px;
    padding: 16px;

    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;

    gap: 10px;

    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: 0.2s ease;
}

.member-card:hover {
    transform: translateY(-3px);
}

/* AVATAR */
.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #7f1d1d;
    color: white;

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: bold;
    font-size: 18px;
}

/* INFO */
.info {
    width: 100%;
}

.info h3 {
    font-size: 15px;
    margin: 0;
}

.email {
    font-size: 12px;
    color: #666;
    margin: 4px 0 8px;
    word-break: break-word;
}

/* BADGE */
.badge {
    display: inline-block;
    font-size: 11px;
    background: #e5e7eb;
    padding: 4px 10px;
    border-radius: 999px;
    margin-top: 6px;
}

/* BUTTON */
.danger {
    width: 100%;
    background: #7f1d1d;
    color: white;
    border: none;

    padding: 8px;
    border-radius: 8px;

    font-size: 13px;
    cursor: pointer;

    transition: 0.2s ease;
}

.danger:hover {
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}
</style>
