<template>
    <div class="page">
        <div class="card">
            <h2>Register Organization</h2>
            <p class="subtext">
                Fill in the details to register a new student organization
            </p>

            <div class="form">
                <div class="group">
                    <label>Organization Name</label>
                    <input
                        v-model="org.org_name"
                        placeholder="e.g. Computer Science Society"
                    />
                </div>

                <div class="group">
                    <label>Category</label>
                    <select v-model="org.category">
                        <option disabled value="">Select a category</option>
                        <option>Academic</option>
                        <option>Civic</option>
                        <option>Cultural</option>
                        <option>Sports</option>
                        <option>Fraternities/Sororities</option>
                        <option>Other</option>
                    </select>
                </div>

                <!-- STATUS -->
                <div class="group">
                    <label>Status</label>
                    <select v-model="org.status">
                        <option value="Registered">Registered</option>
                    </select>
                </div>

                <div class="group">
                    <label>Description</label>
                    <textarea
                        v-model="org.description"
                        placeholder="Describe the organization..."
                    ></textarea>
                </div>
                
                <div class="group">
                    <label>Password</label>
                    <input
                        v-model="org.password"
                    />
                </div>

                <div class="group">
                    <label>Registration Valid Until</label>
                    <input
                        :value="org.expiration"
                        readonly
                        class="readonly-input"
                    />
                    <small class="hint">Automatically set to 1 year from today</small>
                </div>

                <button class="primary" @click="registerOrg">
                    Register Organization
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { post } from '@/services/apiService';
import { useRouter } from 'vue-router';

const router = useRouter();

/* Helper: format YYYY-MM-DD */
const formatDate = (date) => {
    return date.toISOString().split('T')[0];
};

const today = new Date();

/* +1 year expiry */
const expiry = new Date();
expiry.setFullYear(today.getFullYear() + 1);

const org = reactive({
    org_name: '',
    category: '',
    description: '',
    password:'',

    // NEW FIELDS
    status: 'Registered',
    expiration: formatDate(expiry),
});

const registerOrg = async () => {
    try {
        const response = await post('/osa/organizations', org);
        console.log('REGISTER RESPONSE:', response); 

        if (response.status === 'success') {
            alert('Organization registered successfuly!');
            router.push('/osa/orgs');
        }
        
        else {
            console.log('VALIDATION ERRORS:', response.errors);
            alert(response.message || 'Registration failed');
        }
    } catch (err) {
        console.error('Network error:', err);
        alert(err.message || 'Could not connect to the server.');
    }
};
</script>

<style scoped>
.page {
    width: 100%;
    padding-left: 20px;
    padding-top: 20px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
}

/* CARD */
.card {
    width: 100%;
    max-width: 650px;
    background: white;
    padding: 28px;
    border-radius: 14px;
    border-left: 5px solid #7f1d1d;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}

/* TEXT */
h2 {
    margin-bottom: 4px;
}

.subtext {
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 20px;
}

/* FORM */
.form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.group {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

/* INPUTS */
input,
select,
textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-family: inherit;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #7f1d1d;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.readonly-input {
    background-color: #f3f4f6;
    color: #6b7280;
    cursor: not-allowed;
    border-style: dashed;
}

.hint {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

/* BUTTON */
.primary {
    margin-top: 8px;
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border: gold 2px solid;
}
</style>
