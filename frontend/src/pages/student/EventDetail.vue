<template>
  <div>
    <h1>{{ event.name }}</h1>

    <p>{{ event.description }}</p>

    <button @click="join">
      Join Event
    </button>
  </div>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

const event = {
  id: 1,
  name: "Hackathon",
  description: "Coding event"
};

const join = () => {
  router.push("/participate/" + event.id);
};
</script>