<template>
    <div class="search">
        <input type="text" placeholder="Search..." v-model="query" />
    </div>
</template>

<script setup>
import { ref } from 'vue';

const query = ref('');
</script>

<style scoped>
.search input {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    width: 250px;
}
</style>
