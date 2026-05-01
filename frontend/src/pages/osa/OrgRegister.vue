<template>
    <div class="page">
        <h2>Register Organization</h2>

        <div class="card">
           
            <input v-model="form.org_name" placeholder="Organization Name" />

            <select v-model="form.category">
                <option disabled value="">Select Category</option>
                <option value="Academic">Academic</option>
                <option value="Civic">Civic</option>
                <option value="Cultural">Cultural</option>
                <option value="Sports">Sports</option>
                <option value="Fraternities/Sororities">Fraternities/Sororities</option>
            </select>

            <textarea
                v-model="form.description"
                placeholder="Description"
            ></textarea>

            <input
                type="text"
                v-model="form.admin_password"
                readonly
                class="readonly-input"
            />

            <button @click="register">Register Organization</button>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { post } from '@/services/apiService';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const form = reactive({
    org_name: '',
    category: '',
    description: '',
    admin_password:'org_admin123'
});

const register = async () => {
    const payload = {
        org_name: form.org_name,
        category: form.category,
        description: form.description,
    };

    try {
        await post('/osa/organizations', payload);
        alert('Organization Registered Successfully!');
        router.push('/osa/dashboard'); 

    } catch (error) {
        alert('Org registration failed.');
    }
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
/* added styling for password field to show user they can't type there */
.readonly-input {
    
    background-color: #f3f4f6;
    color: #6b7280;
    cursor: not-allowed;
}
</style>
