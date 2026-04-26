<template>
    <div class="landing">
        <!-- HEADER -->
        <header class="header">
            <img src="@/assets/iskonek-logo.png" class="logo" />
        </header>

        <!-- BACKGROUND OVERLAY -->
        <div class="overlay"></div>

        <!-- MAIN CONTENT -->
        <div class="content">
            <p class="subtitle">
                <span>University of the Philippines Mindanao</span><br />
                Student Organization Portal
            </p>

            <div class="buttons">
                <RouterLink to="/student-login" class="btn green">
                    Student Login
                </RouterLink>

                <RouterLink to="/student-register" class="btn maroon">
                    Student Register
                </RouterLink>

                <RouterLink to="/admin-login" class="btn yellow">
                    Admin Login
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { RouterLink } from 'vue-router';
</script>

<style scoped>
/* MAIN BACKGROUND */
.landing {
    height: 100vh;
    width: 100%;
    background-image: url('@/assets/upmin.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    /* make image pop */
    filter: saturate(1.25) contrast(1.15);
}

/* DARK CINEMATIC OVERLAY */
.overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(255, 255, 255, 0.379),
        rgba(13, 107, 41, 0.381)
    );
    z-index: 1;
}

/* HEADER */
.header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    z-index: 2;
    background: rgb(255, 255, 255);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.logo {
    height: 50px;
    object-fit: contain;
}

/* CONTENT CARD */
.content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    max-width: 750px;
    padding: 30px;

    /* glass effect */
    background: rgba(0, 0, 0, 0.25);
    backdrop-filter: blur(6px);
    border-radius: 14px;

    /* glow depth */
    text-shadow: 0 3px 15px rgba(0, 0, 0, 0.8);
}

/* SUBTITLE */
.subtitle {
    font-size: 20px;
    line-height: 1.6;
    margin-bottom: 30px;
    opacity: 0.95;
}

.subtitle span {
    color: #facc15; /* yellow accent */
    font-weight: 700;
    letter-spacing: 1px;
}

/* BUTTONS */
.buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: center;
}

/* BUTTON BASE */
.btn {
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    width: 240px;
    text-align: center;
    font-weight: 600;
    transition: 0.3s;
}

/* COLORS */
.green {
    background: #064e3b;
    color: white;
}

.maroon {
    background: #7f1d1d;
    color: white;
}

.yellow {
    background: #facc15cc;
    color: rgb(255, 255, 255);
}

/* HOVER EFFECT */
.btn:hover {
    transform: scale(1.05);
    opacity: 0.9;
}

/* MOBILE */
@media (max-width: 600px) {
    .logo {
        height: 38px;
    }

    .btn {
        width: 180px;
    }

    .subtitle {
        font-size: 16px;
    }
}
</style>
