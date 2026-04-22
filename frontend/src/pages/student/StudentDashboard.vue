<template>
  <div>
    <h1>Student Dashboard</h1>

    <section>
      <h2>Announcements</h2>
      <div class="card">Admin announcement here</div>
    </section>

    <section>
      <h2>Upcoming Events</h2>

      <div class="event" v-for="event in events" :key="event.id">
        <h3>{{ event.name }}</h3>
        <RouterLink :to="'/event/' + event.id">
          View Details
        </RouterLink>
      </div>
    </section>
  </div>
</template>

<script setup>
const events = [
  { id: 1, name: "Hackathon" },
  { id: 2, name: "Org Fair" }
];
</script>