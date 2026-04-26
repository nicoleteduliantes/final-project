<template>
    <div class="page">
        <h2>Application Form</h2>

        <div class="card">
            <label>Organization</label>
            <input v-model="orgName" disabled />

            <label>Cover Letter</label>
            <textarea v-model="cover"></textarea>

            <label>Committee</label>
            <input v-model="committee" />

            <button @click="submit">Submit Application</button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const orgName = ref('Organization ID: ' + route.params.id);
const cover = ref('');
const committee = ref('');

const submit = () => {
    console.log('submit application');
};
</script>

<style scoped>
.page {
    padding: 20px;
}

.card {
    background: white;
    padding: 20px;
    width: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

input,
textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

textarea {
    min-height: 120px;
}

button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #1d4ed8;
}

label {
    font-weight: 600;
}
</style>
