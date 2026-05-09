<template>
    <nav class="navbar">
        <div class="logo">
            <RouterLink to="/">Iskonek</RouterLink>
        </div>

        <div class="nav-links">
            <RouterLink to="/">Home</RouterLink>
            <RouterLink to="/about">About</RouterLink>
            <RouterLink to="/contact">Contact</RouterLink>
        </div>
    </nav>
</template>

<script setup></script>

<style scoped>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;

    margin-left: 0px;
    padding: 14px 32px;
    background: #7f1d1d;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.logo a {
    color: white;
    text-decoration: none;
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.nav-links {
    display: flex;
    gap: 24px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    transition: 0.2s ease;
    padding-bottom: 4px;
}

.nav-links a:hover {
    color: #facc15;
}

.nav-links a.router-link-active {
    border-bottom: 2px solid #facc15;
}
</style>
