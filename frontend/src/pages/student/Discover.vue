<template>
    <div>
        <h1>Discover</h1>

        <h2>Organizations</h2>

        <div v-for="org in orgs" :key="org.id">
            <h3>{{ org.name }}</h3>

            <RouterLink :to="'/org/' + org.id"> View Profile </RouterLink>
        </div>

        <h2>Events</h2>

        <div v-for="event in events" :key="event.id">
            <h3>{{ event.name }}</h3>

            <RouterLink :to="'/event/' + event.id"> View Event </RouterLink>
        </div>
    </div>
</template>

<script setup>
const orgs = [{ id: 1, name: 'AWS Cloud Club' }];

const events = [{ id: 1, name: 'Tech Talk' }];
</script>
