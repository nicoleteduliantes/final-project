<template>
  <div>
    <h1>{{ org.name }}</h1>

    <p>{{ org.description }}</p>

    <button @click="apply">
      Apply for Membership
    </button>

    <h2>Events</h2>

    <div v-for="event in events" :key="event.id">
      <RouterLink :to="'/event/' + event.id">
        {{ event.name }}
      </RouterLink>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const org = {
  id: 1,
  name: "AWS Cloud Club",
  description: "Cloud community"
};

const events = [
  { id: 1, name: "Cloud Workshop" }
];

const apply = () => {
  router.push("/apply/" + org.id);
};
</script>