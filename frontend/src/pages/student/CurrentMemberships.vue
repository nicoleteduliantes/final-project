<template>
    <div class="page">
        <div class="header">
            <h1>Memberships</h1>
            <p class="subtext">Your organization affiliations</p>
        </div>

        <!-- EMPTY STATE -->
        <div v-if="memberships.length === 0" class="empty-msg">
            No membership records found.
        </div>

        <!-- BOOTH GRID -->
        <div v-else class="booth-grid">
            <div
                class="booth-card"
                v-for="membership in memberships"
                :key="membership.membership_id"
            >
                <!-- CANOPY TOP -->
                <div class="booth-top">
                    <span class="org">
                        {{ membership.organization?.org_name }}
                    </span>

                    <span class="badge">
                        {{ membership.status }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="booth-body">
                    <div class="avatar">
                        {{ getInitials(membership.organization?.org_name) }}
                    </div>

                    <div class="info">
                        <p class="label">Position</p>
                        <p class="value">{{ membership.position }}</p>

                        <p class="label">Committee</p>
                        <p class="value">
                            {{
                                membership.status.toLowerCase() === 'pending'
                                    ? membership.application_detail
                                          ?.applied_committee || 'N/A'
                                    : membership.assigned_committee ||
                                      'General Member'
                            }}
                        </p>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="booth-footer">
                    ID: {{ membership.membership_id }}
                </div>
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
        const res = await get('/memberships');
        memberships.value = res;
    } catch (err) {
        console.error('Failed to load participation records', err);
    }
});

const getInitials = (name = '') => {
    return name
        .split(' ')
        .filter(Boolean)
        .map((n) => n[0])
        .join('')
        .toUpperCase();
};
</script>

<style scoped>
/* PAGE */
.page {
    padding-top: 20px;
    min-height: 100vh;
    background: linear-gradient(180deg, #f8fafc, #f3f4f6);
}

/* HEADER */
.header {
    margin-bottom: 18px;
}

h1 {
    margin: 0;
    font-size: 26px;
    font-weight: 900;
    color: #7f1d1d;
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

/* GRID (fairground layout) */
.booth-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 28px;
    padding: 20px;
    border-radius: 18px;
}

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

.booth-top {
    position: relative;
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

/* BOOTH POLE SHADOW */
.booth-top::after {
    content: '';
    position: absolute;
    bottom: -16px;
    left: 10px;
    right: 10px;
    height: 16px;

    background: linear-gradient(
        to right,
        #6b7280 0px,
        #6b7280 4px,
        transparent 4px,
        transparent calc(100% - 4px),
        #6b7280 calc(100% - 4px)
    );
}

/* ORG NAME */
.org {
    font-size: 13px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* BADGE */
.badge {
    font-size: 10px;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 999px;
    background: #fff;
    color: #1f2937;
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

.booth-footer {
    padding: 10px 14px;

    font-size: 11px;
    color: #f9fafb;

    text-align: right;

    background: linear-gradient(90deg, #374151, #1f2937);

    border-radius: 0 0 14px 14px;

    box-shadow:
        0 12px 0 rgba(0, 0, 0, 0.15),
        0 18px 30px rgba(0, 0, 0, 0.12);
}

/* GROUND SHADOW */
.booth-card::after {
    content: '';
    position: absolute;
    bottom: -18px;
    left: 10%;
    right: 10%;
    height: 18px;

    background: rgba(0, 0, 0, 0.15);
    filter: blur(10px);
    border-radius: 50%;
}
</style>
