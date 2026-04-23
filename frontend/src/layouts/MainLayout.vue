<template>
<div class="layout">

  <component :is="sidebarComponent" />

  <div class="main">
    <Navbar />
    <div class="content">
      <router-view />
    </div>
  </div>

</div>
</template>

<script setup>
import { computed } from "vue"

import Navbar from "@/components/layout/Navbar.vue"
import StudentSidebar from "@/components/layout/sidebar/StudentSidebar.vue"
import OrgSidebar from "@/components/layout/sidebar/OrgSidebar.vue"

const role = localStorage.getItem("role") || "student"

const sidebarComponent = computed(() => {
  return role === "org" ? OrgSidebar : StudentSidebar
})
</script>

<style scoped>
.layout {
display: flex;
height: 100vh;
}

.main {
flex: 1;
display: flex;
flex-direction: column;
}

.content {
padding: 20px;
}
</style>