<template>
  <aside :class="['sidebar', { collapsed }]">
    <div class="top">
      <h2 v-if="!collapsed">ISKONEK</h2>

      <button class="toggle" @click="collapsed = !collapsed">
        ☰
      </button>
    </div>

    <nav class="nav">
      <RouterLink to="/dashboard">
        🏠 <span v-if="!collapsed">Dashboard</span>
      </RouterLink>

      <RouterLink to="/discover">
        🔍 <span v-if="!collapsed">Discover</span>
      </RouterLink>

      <RouterLink to="/memberships">
        👥 <span v-if="!collapsed">Current Memberships</span>
      </RouterLink>

      <RouterLink to="/calendar">
        📅 <span v-if="!collapsed">Event Calendar</span>
      </RouterLink>

      <RouterLink to="/participation">
        📊 <span v-if="!collapsed">Participation Record</span>
      </RouterLink>
    </nav>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { RouterLink } from "vue-router";

const collapsed = ref(false);
</script>

<style scoped>
.sidebar {
  width: 220px;
  height: 100vh;
  background: #111827;
  color: white;
  padding: 20px;
  transition: 0.3s;
}

.sidebar.collapsed {
  width: 70px;
}

.top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.toggle {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
}

.nav {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 20px;
}

a {
  color: white;
  text-decoration: none;
  display: flex;
  gap: 10px;
  align-items: center;
}
</style>