<template>
<div class="page">
<h2>Create New Event</h2>

<div class="form">

<label>Event Name</label>
<input v-model="event.name"/>

<label>Event Host/s</label>
<input v-model="event.hosts"/>

<label>Event Logo</label>
<input type="file"/>

<label>Event Date</label>
<input type="date" v-model="event.date"/>

<label>Description</label>
<textarea v-model="event.desc"></textarea>

<button class="primary">Create Event</button>

</div>
</div>
</template>

<script setup>
import { reactive } from "vue"

const event = reactive({
    name:"",
    hosts:"",
    date:"",
    desc:""
})
</script>