<template>
    <div class="page">
        <div class="card">
            <h1>Create New Event</h1>
            <p class="subtext">
                Fill in the details to publish a new organization event
            </p>

            <div class="form">
                <div class="group">
                    <label>Event Name</label>
                    <input
                        v-model="event.event_name"
                        placeholder="e.g. General Assembly"
                    />
                </div>

                <div class="row">
                    <div class="group">
                        <label>Date</label>
                        <input 
                        type="date" 
                        v-model="event.date" 
                        :min="new Date().toLocaleDateString('en-CA')"/>
                    </div>

                    <div class="group">
                        <label>Location</label>
                        <input
                            v-model="event.location"
                            placeholder="e.g. Auditorium"
                        />
                    </div>
                </div>

                <div class="group">
                    <label>Description</label>
                    <textarea
                        v-model="event.description"
                        placeholder="Describe the event..."
                    ></textarea>
                </div>

                <button class="primary" @click="createEvent">
                    Create Event
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

const event = reactive({
    event_name: '',
    date: '',
    location: '',
    description: '',
});

const createEvent = async () => {
    try {
        const response = await post('/org/events', event);
        
        console.log('CREATE EVENT:', response); 

        if (response.status === 'success') {
            alert('Event Creation Successful!');
            router.push('/org/events');
        }
        
        else {
            console.log('VALIDATION ERRORS:', response.errors);
            alert(response.message || 'Event Creation failed');
        }
    } catch (err) {
        console.error('Network error:', err);
        alert(err.message||'Could not connect to the server.');
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
    padding-top: 0px;
    border-radius: 14px;
    border-left: 5px solid #7f1d1d;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
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

/* ROW */
.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

/* GROUP */
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
textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: 0.2s;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #7f1d1d;
}

/* BUTTON */
.primary {
    margin-top: 8px;
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: #7f1d1d 3px solid;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border: gold 3px solid;
}

/* MOBILE */
@media (max-width: 600px) {
    .row {
        grid-template-columns: 1fr;
    }
}
</style>
