<template>
    <div class="page">
        <!-- HEADER -->
        <div class="header">
            <h1>Organization Registration</h1>
            <p class="subtext">
                Create and register a new student organization
            </p>
        </div>

        <!-- FORM -->
        <div class="form">
            <!-- ROW GRID -->
            <div class="grid">
                <div class="group full">
                    <label>Organization Name</label>
                    <input
                        v-model="org.org_name"
                        placeholder="e.g. Computer Science Society"
                    />
                </div>

                <div class="group">
                    <label>Category</label>
                    <select v-model="org.category">
                        <option disabled value="">Select category</option>
                        <option>Academic</option>
                        <option>Civic</option>
                        <option>Cultural</option>
                        <option>Sports</option>
                        <option>Fraternities/Sororities</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="group">
                    <label>Status</label>
                    <select v-model="org.status">
                        <option value="Registered">Registered</option>
                    </select>
                </div>

                <div class="group full">
                    <label>Description</label>
                    <textarea
                        v-model="org.description"
                        placeholder="Describe the organization's purpose, mission, and activities..."
                    ></textarea>
                </div>

                <div class="group">
                    <label>Password</label>
                    <input
                        v-model="org.password"
                        type="password"
                        placeholder="Create organization password"
                    />
                </div>

                <div class="group">
                    <label>Valid Until</label>
                    <input
                        :value="org.expiration"
                        readonly
                        class="readonly-input"
                    />
                    <small class="hint">
                        Automatically set to 1 year from registration
                    </small>
                </div>
            </div>

            <!-- ACTION -->
            <div class="actions">
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
    password: '',

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
        } else {
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
    padding: clamp(20px, 10vw, 50px);
    min-height: 100vh;
    width: 100%;
}

/* HEADER */
.header {
    margin-bottom: 30px;
}

h1 {
    color: #7f1d1d;
    margin-bottom: 6px;
}

.subtext {
    color: #6b7280;
    font-size: 14px;
}

/* FORM */
.form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px 20px;
}

/* FULL WIDTH */
.full {
    grid-column: span 2;
}

/* GROUP */
.group {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #374151;
}

/* INPUTS */
input,
select,
textarea {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: white;
    font-family: inherit;
    transition: 0.2s;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #064e3b;
    box-shadow: 0 0 0 2px rgba(6, 78, 59, 0.1);
    outline: none;
}

textarea {
    min-height: 140px;
    resize: vertical;
}

/* READONLY */
.readonly-input {
    background: #f3f4f6;
    border-style: dashed;
    color: #6b7280;
    cursor: not-allowed;
}

/* HINT */
.hint {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
}

/* ACTIONS */
.actions {
    margin-top: 10px;
}

/* BUTTON */
.primary {
    padding: 14px 18px;
    background: #7f1d1d;
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    width: 260px;
}

.primary:hover {
    background: #064e3b;
    color: gold;
}

/* MOBILE */
@media (max-width: 768px) {
    .page {
        padding: 25px;
    }

    .grid {
        grid-template-columns: 1fr;
    }

    .full {
        grid-column: span 1;
    }

    .primary {
        width: 100%;
    }
}
</style>
