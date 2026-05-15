<template>
    <div class="landing">
        <!-- OVERLAY -->
        <div class="overlay"></div>

        <!-- FLOATING CAMPUS ELEMENTS -->
        <div
            v-for="(emoji, index) in stickers"
            :key="index"
            :class="['parallax-layer', `sticker${index}`]"
            :style="getStickerStyle(index)"
        >
            <div class="floating">
                {{ emoji }}
            </div>
        </div>

        <!-- PUSH PINS -->
        <div class="pin pin1"></div>
        <div class="pin pin2"></div>

        <!-- MAIN CONTENT -->
        <div class="content">
            <!-- Center Logo -->
            <div class="identity-block">
                <img src="@/assets/iskonek-logo.png" class="main-logo" />

                <div class="id-badge">CAMPUS CONNECTIONS AND COMMUNITY</div>
            </div>

            <p class="subtitle">
                <span>University of the Philippines Mindanao</span><br />
                Student Organization Portal
            </p>

            <div class="buttons">
                <RouterLink to="/student-login" class="btn student">
                    Student Login
                </RouterLink>

                <RouterLink to="/admin-login" class="btn admin">
                    Admin Login
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const stickers = [
    '🎓',
    '📚',
    '📌',
    '✨',
    '📝',
    '💻',
    '🏫',
    '🎨',
    '📖',
    '🌟',
    '🧠',
];

const mouseX = ref(0);
const mouseY = ref(0);

const handleMouseMove = (e) => {
    mouseX.value = (e.clientX / window.innerWidth - 0.5) * 2;
    mouseY.value = (e.clientY / window.innerHeight - 0.5) * 2;
};

const getStickerStyle = (index) => {
    const intensity = ((index % 5) + 1) * 10;

    return {
        transform: `translate(${mouseX.value * intensity}px, ${mouseY.value * intensity}px)`,
    };
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<style scoped>
/* PAGE */
.landing {
    min-height: 100vh;
    width: 100%;
    position: relative;
    overflow: hidden;

    background-image: url('@/assets/upmin.jpg');
    background-size: cover;
    background-position: center;

    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0px;

    filter: saturate(1.1) contrast(1.05);
}

/* OVERLAY */
.overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(6, 78, 59, 0.45));
    z-index: 1;
}

/* MAIN CARD */
.identity-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;

    margin-bottom: 1.5rem;
}
.content {
    position: relative;
    z-index: 2;

    width: 100%;
    max-width: 620px;

    text-align: center;

    background: rgba(255, 255, 255, 0.14);
    backdrop-filter: blur(16px);

    padding: 45px;
    border-radius: 24px;

    border: 1px solid rgba(255, 255, 255, 0.25);

    box-shadow:
        0 0 0 2px rgba(250, 204, 21, 0.12),
        0 18px 45px rgba(0, 0, 0, 0.35);
}

/* CENTER LOGO */
.main-logo {
    width: 240px;
    margin-bottom: 14px;

    background: rgba(255, 255, 255, 0.95);
    padding: 14px 22px;
    border-radius: 18px;

    box-shadow:
        0 6px 20px rgba(0, 0, 0, 0.18),
        0 0 0 2px rgba(255, 255, 255, 0.4);
}

/* BADGE */
.id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    background: #facc15;
    color: #064e3b;

    padding: 6px 16px;
    border-radius: 999px;

    font-size: 12px;
    font-weight: 800;
    letter-spacing: 1px;

    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);

    transform: translateY(-4px);
}

/* SUBTITLE */
.subtitle {
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 35px;
    color: white;
}

.subtitle span {
    font-weight: 700;
}

/* BUTTONS */
.buttons {
    display: flex;
    flex-direction: column;
    gap: 18px;
    align-items: center;
}

/* POSTER BUTTONS */
.btn {
    width: 260px;
    padding: 15px;

    text-decoration: none;
    font-weight: 700;
    font-size: 16px;

    border: 3px solid white;
    border-radius: 10px;

    box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2);

    transition: all 0.25s ease;
}

/* BUTTON COLORS */
.student {
    background: #7f1d1d;
    color: white;
}

.admin {
    background: #facc15;
    color: #1f2937;
}

.student,
.admin {
    transition: all 0.25s ease;
}

.student:hover,
.admin:hover {
    background: #064e3b;
    color: gold;
    border: 3px solid gold;

    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    scale: 1.03;
}

/* PUSH PINS */
.pin {
    position: absolute;
    z-index: 3;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #dc2626;

    box-shadow:
        inset -3px -3px 6px rgba(0, 0, 0, 0.25),
        0 3px 6px rgba(0, 0, 0, 0.25);
}

.pin1 {
    top: calc(50% - 250px);
    left: calc(50% - 260px);
}

.pin2 {
    top: calc(50% - 250px);
    right: calc(50% - 260px);
}

/* FLOATING CAMPUS ICONS */

.parallax-layer {
    position: absolute;
    transition: transform 0.2s ease-out;
    pointer-events: none;
    z-index: 1;
    will-change: transform;
}

.floating {
    position: absolute;
    font-size: 2rem;
    animation: float 6s ease-in-out infinite;
    transition: transform 0.15s ease-out;
    pointer-events: none;
    will-change: transform;
    z-index: 1;
}

.sticker1 {
    top: 14%;
    left: 8%;
    font-size: 36px;
}

.sticker2 {
    top: 18%;
    right: 10%;
    font-size: 28px;
}

.sticker3 {
    top: 28%;
    right: 5%;
    font-size: 40px;
}

.sticker4 {
    bottom: 18%;
    left: 12%;
    font-size: 32px;
}

.sticker5 {
    bottom: 22%;
    right: 18%;
    font-size: 24px;
}

.sticker6 {
    top: 42%;
    left: 5%;
    font-size: 26px;
}

.sticker7 {
    bottom: 35%;
    left: 17%;
    font-size: 32px;
}

.sticker8 {
    bottom: 12%;
    left: 28%;
    font-size: 22px;
}

.sticker9 {
    top: 48%;
    right: 18%;
    font-size: 18px;
}

.sticker10 {
    bottom: 30%;
    right: 8%;
    font-size: 26px;
}

.sticker11 {
    top: 32%;
    left: 14%;
    font-size: 20px;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-12px);
    }
}

/* MOBILE */
@media (max-width: 768px) {
    .content {
        padding: 30px 24px;
        transform: none;
    }

    .main-logo {
        width: 180px;
    }

    .subtitle {
        font-size: 16px;
    }

    .btn {
        width: 220px;
    }

    .floating,
    .pin {
        display: none;
    }

    .header-logo {
        height: 38px;
    }
}

@media (max-width: 480px) {
    .content {
        width: 100%;
        max-width: 310px;
    }
}
</style>
