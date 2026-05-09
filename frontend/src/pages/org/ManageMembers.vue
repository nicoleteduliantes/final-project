<template>
    <div class="page">
        <h2>Manage Members</h2>

        <div class="grid">
            <div
                class="member-card"
                v-for="m in members"
                :key="m.membership_id"
            >
                <div class="card-header">ISKONEK MEMBER ID</div>

                <div class="card-body">
                    <div class="avatar">
                        {{ getInitials(m.student_name) }}
                    </div>

                    <div class="info">
                        <h3>{{ m.student_name }}</h3>
                        <p class="email">{{ m.email }}</p>

                        <span class="badge">
                            {{ m.assigned_committee }}
                        </span>

                        <p class="id">ID: {{ m.membership_id }}</p>
                        <p class="expiry">Valid Until: {{ m.valid_until }}</p>
                    </div>
                </div>

                <div class="card-footer">
                    <button
                        class="danger"
                        @click="removeMember(m.membership_id)"
                    >
                        Remove Member
                    </button>
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

                // expiry field (1 year validity)
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
/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

/* ID CARD */
.member-card {
    background: white;
    border-radius: 14px;
    border: 1px solid #e5e7eb;

    width: 280px;
    min-height: 190px;

    display: flex;
    flex-direction: column;

    overflow: hidden;

    transition: 0.2s ease;
}

.member-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

/* HEADER */
.card-header {
    background: #7f1d1d;
    color: white;
    font-size: 11px;
    letter-spacing: 1px;
    padding: 6px;
    text-align: center;
}

/* BODY */
.card-body {
    display: flex;
    gap: 12px;
    padding: 12px;
    flex: 1;
    align-items: center;
}

/* AVATAR (ID PHOTO STYLE) */
.avatar {
    width: 55px;
    height: 55px;
    border-radius: 8px;
    background: #7f1d1d;
    color: white;

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: bold;
}

/* INFO */
.info {
    flex: 1;
    text-align: left;
}

.info h3 {
    font-size: 14px;
    margin: 0;
}

.email {
    font-size: 11px;
    color: #666;
    margin: 2px 0;
}

.badge {
    font-size: 10px;
    background: #f3f4f6;
    padding: 3px 8px;
    border-radius: 999px;
    display: inline-block;
    margin-top: 4px;
}

.id,
.expiry {
    font-size: 10px;
    color: #888;
    margin-top: 4px;
}

/* FOOTER */
.card-footer {
    padding: 8px;
    border-top: 1px solid #eee;
}

.danger {
    width: 100%;
    background: transparent;
    border: 1px solid #7f1d1d;
    color: #7f1d1d;

    padding: 6px;
    border-radius: 6px;
    font-size: 12px;

    cursor: pointer;
    transition: 0.2s ease;
}

.danger:hover {
    background: #7f1d1d;
    color: white;
}
</style>
