<template>
    <div class="page">
        <div class="form-container">
            <h2 class="page-title">Register Organization</h2>

            <div class="card">
                <!-- Org Name -->
                <div class="form-group">
                    <input
                        v-model="form.org_name"
                        placeholder="Organization Name"
                        :class="{ error: errors.org_name }"
                    />
                    <small v-if="errors.org_name">{{ errors.org_name }}</small>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <select
                        v-model="form.category"
                        :class="{ error: errors.category }"
                    >
                        <option disabled value="">Select Category</option>
                        <option value="Academic">Academic</option>
                        <option value="Civic">Civic</option>
                        <option value="Cultural">Cultural</option>
                        <option value="Sports">Sports</option>
                        <option value="Fraternities/Sororities">
                            Fraternities/Sororities
                        </option>
                    </select>
                    <small v-if="errors.category">{{ errors.category }}</small>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <textarea
                        v-model="form.description"
                        placeholder="Description"
                        :class="{ error: errors.description }"
                    ></textarea>
                    <small v-if="errors.description">{{
                        errors.description
                    }}</small>
                </div>

                <!-- Admin Name -->
                <div class="form-group">
                    <input
                        type="text"
                        v-model="form.admin_name"
                        placeholder="Admin Name"
                        :class="{ error: errors.admin_name }"
                    />
                    <small v-if="errors.admin_name">{{
                        errors.admin_name
                    }}</small>
                </div>

                <!-- Admin Password -->
                <div class="form-group">
                    <input
                        type="password"
                        v-model="form.admin_password"
                        readonly
                        class="readonly-input"
                    />
                </div>

                <button @click="register">Register Organization</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { post } from '@/services/apiService';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = reactive({
    org_name: '',
    category: '',
    description: '',
    admin_password: 'org_admin123',
});

const errors = reactive({
    org_name: '',
    category: '',
    description: '',
});

const validate = () => {
    errors.org_name = form.org_name ? '' : 'Organization name is required';
    errors.category = form.category ? '' : 'Category is required';
    errors.description = form.description ? '' : 'Description is required';

    return !errors.org_name && !errors.category && !errors.description;
};

const register = async () => {
    if (!validate()) return;

    try {
        await post('/osa/organizations', form);
        alert('Organization Registered Successfully!');
        router.push('/osa/dashboard');
    } catch (error) {
        alert('Org registration failed.');
    }
};
</script>

<style scoped>
.page {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.form-container {
    width: 100%;
    max-width: 500px; /* same as card */
}

/* center relative to container (form width) */
.page-title {
    text-align: center;
    margin-bottom: 15px;
}

.card {
    background: white;
    padding: 15px;
    width: 100%;
    max-width: 500px; /* responsive instead of fixed */
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}

/* group wrapper */
.form-group {
    display: flex;
    flex-direction: column;
}

/* inputs */
input,
select,
textarea {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    width: 100%; /* prevents cutoff */
    box-sizing: border-box;
}

/* textarea height */
textarea {
    min-height: 90px;
    resize: vertical;
}

/* button */
button {
    background: #7f1d1d;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background: rgb(3, 74, 46);
    color: gold;
    border: 2px solid gold;
}

/* readonly */
.readonly-input {
    background-color: #f3f4f6;
    color: #6b7280;
    cursor: not-allowed;
}

/* validation styles */
.error {
    border-color: #ef4444;
}

small {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
}

/* mobile tweaks */
@media (max-width: 480px) {
    .page {
        padding: 10px;
    }

    .card {
        padding: 15px;
    }
}
</style>
