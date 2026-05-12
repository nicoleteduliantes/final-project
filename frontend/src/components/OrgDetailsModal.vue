<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal-content">
            <!-- CLOSE BUTTON -->
            <button class="close-btn" @click="$emit('close')">✕</button>

            <!-- BANNER -->
            <div class="banner-wrapper">
                <img
                    :src="org.banner_url || fallbackBanner"
                    class="modal-banner"
                />
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <!-- TITLE -->
                <h2 class="title">{{ org.org_name }}</h2>

                <!-- CATEGORY -->
                <span
                    class="modal-cat"
                    :style="{ color: getColor(org.category) }"
                >
                    {{ org.category }}
                </span>

                <!-- DESCRIPTION -->
                <p class="modal-description">
                    {{ org.description || 'No detailed description provided.' }}
                </p>

                <!-- ACTIONS -->
                <div class="modal-actions">
                    <button
                        v-if="isMember(org.org_id) === 'Accepted'"
                        class="member-status"
                        disabled
                    >
                        ✓ Already a Member
                    </button>

                    <button
                        v-else-if="isMember(org.org_id) === 'Pending'"
                        class="member-status"
                        disabled
                    >
                        ⏳ Application Pending
                    </button>

                    <button
                        v-else-if="org.application_status === 'closed'"
                        class="member-status"
                        disabled
                    >
                        🚫 Application Closed
                    </button>

                    <button
                        v-else
                        class="apply-btn"
                        @click="$emit('apply', org)"
                    >
                        Apply to Join
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    org: Object,
    isMember: Function,
    getColor: Function,
    fallbackBanner: String,
});

defineEmits(['close', 'apply']);
</script>

<style scoped>
/* OVERLAY */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(6px);
}

/* CARD */
.modal-content {
    width: 92%;
    max-width: 520px;
    background: white;
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
    animation: pop 0.25s ease;
}

/* CLOSE BUTTON */
.close-btn {
    position: absolute;
    top: 12px;
    right: 12px;

    width: 36px;
    height: 36px;
    border-radius: 50%;

    border: 1px solid #e5e7eb;
    background: white;
    color: #7f1d1d;

    font-weight: 900;
    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    z-index: 10;
    transition: 0.2s ease;
}

.close-btn:hover {
    background: #7f1d1d;
    color: white;
    transform: scale(1.05);
}

/* BANNER */
.banner-wrapper {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.modal-banner {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* BODY */
.modal-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* TITLE */
.title {
    margin: 0;
    font-size: 22px;
    font-weight: 800;
    color: #1f2937;
}

/* CATEGORY */
.modal-cat {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

/* DESCRIPTION */
.modal-description {
    margin: 10px 0 18px;
    font-size: 14px;
    color: #475569;
    line-height: 1.5;
}

/* ACTIONS */
.modal-actions {
    margin-top: auto;
}

/* BUTTONS */
.apply-btn,
.member-status {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s ease;
}

.apply-btn {
    background: #7f1d1d;
    color: white;
    border: 3px solid #7f1d1d;
}

.apply-btn:hover {
    background: #034a2e;
    color: gold;
    border: 3px solid gold;
    transform: translateY(-1px);
}

.apply-btn.disabled {
    background: #94a3b8;
    border: none;
    cursor: not-allowed;
    opacity: 0.7;
}

.apply-btn:disabled {
    pointer-events: none;
}

.member-status {
    background: #f1f5f9;
    color: #64748b;
    border: none;
    cursor: not-allowed;
}

/* ANIMATION */
@keyframes pop {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        max-width: 420px;
        border-radius: 14px;
    }

    .banner-wrapper {
        height: 140px; /* ↓ smaller banner */
    }

    .modal-body {
        padding: 14px;
        gap: 6px;
    }

    .title {
        font-size: 18px; /* ↓ shrink title */
    }

    .modal-cat {
        font-size: 10px;
    }

    .modal-description {
        font-size: 12px;
        margin: 6px 0 12px;
        line-height: 1.4;
    }

    .close-btn {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .apply-btn,
    .member-status {
        padding: 10px;
        font-size: 12px;
    }
}

/* EXTRA SMALL PHONES */
@media (max-width: 480px) {
    .modal-content {
        width: 96%;
    }

    .banner-wrapper {
        height: 120px;
    }

    .title {
        font-size: 16px;
    }

    .modal-description {
        font-size: 11px;
    }
}
</style>
