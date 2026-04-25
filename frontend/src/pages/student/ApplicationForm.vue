<template>
    <div>
        <h1>Application Form</h1>

        <input v-model="orgName" disabled />

        <textarea placeholder="Cover Letter" v-model="cover"></textarea>

        <input placeholder="Committee" v-model="committee" />

        <button @click="submit">Submit Application</button>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const orgName = ref('AWS Cloud Club');
const cover = ref('');
const committee = ref('');

const submit = () => {
    console.log('submit application');
};
</script>
