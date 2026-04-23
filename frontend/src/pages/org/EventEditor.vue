<template>
<div class="page">
    <h2>Event Editor</h2>

    <div class="form">
        <label>Event Name</label>
        <input v-model="event.name" />

        <label>Event Host/s</label>
        <input v-model="event.hosts" />

        <label>Event Date</label>
        <input type="date" v-model="event.date" />

        <label>Description</label>
        <textarea v-model="event.description"></textarea>

        <button class="save">Save Changes</button>
    </div>
</div>
</template>

<script setup>
import { reactive } from "vue"

const event = reactive({
    name: "",
    hosts: "",
    date: "",
    description: ""
})
</script>

<style scoped>
.page {
    padding: 20px;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 400px;
}

input, textarea {
    padding: 8px;
    border: 1px solid #ccc;
}

.save {
    background: #2563eb;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}
</style>