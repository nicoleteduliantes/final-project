<template>
  <div class="layout">
    <component :is="sidebarComponent" />

    <div class="main">
      <Navbar />
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useAuthStore } from "../stores/auth";

import StudentSidebar from "../components/layout/sidebars/StudentSidebar.vue";
import OrgSidebar from "../components/layout/sidebars/OrgSidebar.vue";
import OsaSidebar from "../components/layout/sidebars/OsaSidebar.vue";

import Navbar from "../components/layout/Navbar.vue";

const auth = useAuthStore();

const sidebarComponent = computed(() => {
  if (auth.role === "student") return StudentSidebar;
  if (auth.role === "org") return OrgSidebar;
  if (auth.role === "osa") return OsaSidebar;

  return StudentSidebar; 
});
</script>

<style scoped>
.layout {
  display: flex;
}

.main {
  flex: 1;
}
</style>