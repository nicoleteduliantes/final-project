<template>
    <div class="page">
        <div class="card">
            <h2>Event Editor</h2>
            <p class="subtext">Update the details of your organization event</p>

            <div class="form">
                <div class="group">
                    <label>Event Name</label>
                    <input v-model="event.event_name" />
                </div>

                <div class="row">
                    <div class="group">
                        <label>Date</label>
                        <input type="date" v-model="event.date" />
                    </div>

                    <div class="group">
                        <label>Location</label>
                        <input v-model="event.location" />
                    </div>
                </div>

                <div class="group">
                    <label>Description</label>
                    <textarea v-model="event.description"></textarea>
                </div>

                <button class="primary" @click="updateEvent">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { get, put } from '@/services/apiService';

const route = useRoute();
const router = useRouter();
const eventId = route.params.id;

const event = reactive({
    event_name: '',
    date: '',
    location: '',
    description: '',
});

const loadEvent = async () => {
    try {
        const data = await get(`/org/events/${eventId}`);

        event.event_name = data.event_name;
        event.date = data.date;
        event.location = data.location;
        event.description = data.description;
    } catch (error) {
        alert('Failed to load event.');
        console.error(error);
    }
};

const updateEvent = async () => {
    try {
        await put(`/org/events/${eventId}`, event);
        alert('Event updated successfully!');
        router.push('/org/events');
    } catch (error) {
        alert('Update failed.');
        console.error(error);
    }
};

onMounted(loadEvent);
</script>

<style scoped>
.page {
    width: 100%;
    padding: 20px;

    display: flex;
    flex-direction: column;
    align-items: flex-start;

    text-align: left;
}

/* CARD (same as create event) */
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

/* TEXTAREA */
textarea {
    min-height: 100px;
    resize: vertical;
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

/* MOBILE */
@media (max-width: 600px) {
    .row {
        grid-template-columns: 1fr;
    }
}
</style>
