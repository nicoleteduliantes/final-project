<template>
    <div class="page">
        <h2>Register Organization</h2>

        <div class="card">
            <input v-model="form.org_id" placeholder="Organization ID" />

            <input v-model="form.org_name" placeholder="Organization Name" />

            <select v-model="form.category">
                <option disabled value="">Select Category</option>
                <option value="Academic">Academic</option>
                <option value="Non-Academic">Non-Academic</option>
                <option value="University-Wide">University-Wide</option>
                <option value="College-Based">College-Based</option>
            </select>

            <textarea
                v-model="form.description"
                placeholder="Description"
            ></textarea>

            <input v-model="form.org_admin" placeholder="Organization Admin" />

            <input
                type="password"
                v-model="form.admin_password"
                placeholder="Admin Password"
            />

            <button @click="register">Register Organization</button>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({
    org_id: '',
    org_name: '',
    category: '',
    description: '',
    org_admin: '',
    admin_password: '',
});

const register = () => {
    console.log('ORG REGISTER:', form);
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
    gap: 10px;
}

input,
select,
textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
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
</style>
