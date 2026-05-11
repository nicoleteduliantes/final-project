<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="modelValue" class="overlay" @click.self="close">
                <div class="card" role="dialog" aria-modal="true">
                    <div class="header">
                        <div>
                            <h1>Create New Event</h1>
                            <p class="subtext">
                                Fill in the details to publish a new
                                organization event
                            </p>
                        </div>
                        <button
                            class="close-btn"
                            @click="close"
                            aria-label="Close modal"
                        >
                            ×
                        </button>
                    </div>

                    <form class="form" @submit.prevent="createEvent">
                        <div class="group">
                            <label for="event_name">Event Name</label>
                            <input
                                id="event_name"
                                v-model.trim="event.event_name"
                                placeholder="e.g. General Assembly"
                                required
                            />
                        </div>

                        <div class="row">
                            <div class="group">
                                <label for="date">Date</label>
                                <input
                                    id="date"
                                    type="date"
                                    v-model="event.date"
                                    required
                                />
                            </div>

                            <div class="group">
                                <label for="location">Location</label>
                                <input
                                    id="location"
                                    v-model.trim="event.location"
                                    placeholder="e.g. Auditorium"
                                    required
                                />
                            </div>
                        </div>

                        <div class="group">
                            <label for="description">Description</label>
                            <textarea
                                id="description"
                                v-model.trim="event.description"
                                placeholder="Describe the event..."
                                rows="4"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            class="primary"
                            :disabled="isLoading"
                        >
                            <span v-if="isLoading">Creating...</span>
                            <span v-else>Create Event</span>
                        </button>
                    </form>
                </div>

                <Transition name="slide-up">
                    <div v-if="toast.show" class="toast" :class="toast.type">
                        {{ toast.message }}
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { post } from '@/services/apiService';

const props = defineProps({
    modelValue: Boolean,
});

const emit = defineEmits(['update:modelValue', 'created']);

const isLoading = ref(false);

const close = () => {
    if (isLoading.value) return;
    emit('update:modelValue', false);
};

/* FORM STATE */
const emptyEvent = () => ({
    event_name: '',
    date: '',
    location: '',
    description: '',
});

const event = reactive(emptyEvent());

/* TOAST STATE */
const toast = reactive({
    show: false,
    message: '',
    type: 'success',
});

const showToast = (msg, type = 'success') => {
    toast.message = msg;
    toast.type = type;
    toast.show = true;
    setTimeout(() => {
        toast.show = false;
    }, 3000);
};

/* CREATE LOGIC */
const createEvent = async () => {
    if (isLoading.value) return;

    isLoading.value = true;
    try {
        await post('/org/events', event);

        showToast('Event Created Successfully!');
        Object.assign(event, emptyEvent());

        setTimeout(() => {
            emit('created');
            close();
        }, 1000);
    } catch (e) {
        showToast('Creation failed. Please try again.', 'error');
    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
/* ANIMATIONS */
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
.slide-up-enter-from {
    transform: translate(-50%, 20px);
    opacity: 0;
}

/* LAYOUT */
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
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
}

h1 {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    margin: 0;
}
.subtext {
    color: #6b7280;
    font-size: 14px;
    margin: 4px 0 0 0;
}

.close-btn {
    background: #d2d2d4;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: 0.2s;
}

.close-btn:hover {
    background: #7b7c7f;
}

/* FORM ELEMENTS */
.form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
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
    font-size: 15px;
    transition: border-color 0.2s;
}

input:focus,
textarea:focus {
    border-color: #7f1d1d;
    outline: none;
    box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
}

/* BUTTONS */
.primary {
    padding: 14px;
    background: #7f1d1d;
    color: white;
    border: #7f1d1d 3px solid;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
}

.primary:hover:not(:disabled) {
    background: #064e3b;
    color: #fbbf24;
    border-color: #fbbf24;
}

.primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    filter: grayscale(1);
}

/* TOAST */
.toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    z-index: 10000;
    background: #7f1d1d; /* Fallback */
}

.toast.error {
    background: #dc2626;
}
</style>
