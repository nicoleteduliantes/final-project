<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="modelValue" class="overlay" @click.self="close">
                <div class="card" role="dialog" aria-modal="true">
                    <div class="header">
                        <div>
                            <h1>Edit Event</h1>
                            <p class="subtext">
                                Update the details of your organization event
                            </p>
                        </div>

                        <button class="close-btn" @click="close">✕</button>
                    </div>

                    <form class="form" @submit.prevent="updateEvent">
                        <div class="group">
                            <label>Event Name</label>
                            <input v-model="event.event_name" />
                        </div>

                        <div class="row">
                            <div class="group">
                                <label>Date</label>
                                <input
                                    type="date"
                                    v-model="event.date"
                                    :min="
                                        new Date().toLocaleDateString('en-CA')
                                    "
                                />
                            </div>

                            <div class="group">
                                <label>Location</label>
                                <input v-model="event.location" />
                            </div>
                        </div>

                        <div class="group">
                            <label>Description</label>
                            <textarea
                                v-model="event.description"
                                rows="4"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            class="primary"
                            :disabled="isLoading"
                        >
                            <span v-if="isLoading">Saving...</span>
                            <span v-else>Save Changes</span>
                        </button>
                    </form>
                </div>

                <Transition name="slide-up">
                    <div v-if="toast.show" class="toast">
                        {{ toast.message }}
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';
import { get, put } from '@/services/apiService';

const props = defineProps({
    modelValue: Boolean,
    eventId: Number,
});

const emit = defineEmits(['update:modelValue', 'updated']);

const isLoading = ref(false);

/* MODAL CLOSE */
const close = () => {
    if (isLoading.value) return;
    emit('update:modelValue', false);
};

/* EVENT STATE */
const event = reactive({
    event_name: '',
    date: '',
    location: '',
    description: '',
});

/* TOAST */
const toast = reactive({
    show: false,
    message: '',
});

const showToast = (msg) => {
    toast.message = msg;
    toast.show = true;
    setTimeout(() => (toast.show = false), 2500);
};

/* LOAD EVENT */
const loadEvent = async () => {
    if (!props.eventId) return;

    try {
        const res = await get(`/org/events/${props.eventId}`);
        const data = res?.data?.data ?? res?.data ?? res;

        event.event_name = data.event_name;
        event.date = data.date;
        event.location = data.location;
        event.description = data.description;
    } catch (err) {
        showToast('Failed to load event');
        console.error(err);
    }
};

/* UPDATE EVENT */
const updateEvent = async () => {
    if (isLoading.value) return;

    isLoading.value = true;

    try {
        await put(`/org/events/${props.eventId}`, event);

        showToast('Event updated successfully');

        setTimeout(() => {
            emit('updated');
            close();
        }, 800);
    } catch (err) {
        console.error(err);
        showToast('Update failed');
    } finally {
        isLoading.value = false;
    }
};

/* WATCH OPEN */
watch(
    () => props.modelValue,
    (open) => {
        if (open) loadEvent();
    }
);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.3s ease;
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.card {
    width: 90%;
    max-width: 600px;
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

.header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
}

h1 {
    font-size: 22px;
    font-weight: 800;
    margin: 0;
    color: #111827;
}

.subtext {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
}

.close-btn {
    border: none;
    background: transparent;
    font-size: 22px;
    cursor: pointer;
    color: #6b7280;
}

.close-btn:hover {
    color: #7f1d1d;
}

/* FORM */
.form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

label {
    font-size: 13px;
    font-weight: 700;
    color: #4b5563;
}

input,
textarea {
    padding: 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #7f1d1d;
    box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
}

/* BUTTON */
.primary {
    padding: 12px;
    background: #7f1d1d;
    color: white;
    border: 3px solid #7f1d1d;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.primary:hover {
    background: #064e3b;
    color: gold;
    border-color: gold;
}

/* TOAST */
.toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #7f1d1d;
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
}
</style>
