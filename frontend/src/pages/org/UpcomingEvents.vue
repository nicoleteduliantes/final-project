<template>
    <div class="page-container">
        <header class="header">
            <div>
                <h1>Upcoming Events</h1>
                <p class="subtext">
                    Manage and monitor your organization's schedule
                </p>
            </div>

            <button class="create-btn" @click="showCreate = true">
                + Create Event
            </button>
        </header>

        <div v-if="isLoading" class="poster-grid">
            <div v-for="i in 3" :key="i" class="poster skeleton-card">
                <div class="skeleton-image skeleton"></div>
                <div class="poster-content">
                    <div class="skeleton title"></div>
                    <div class="skeleton text"></div>
                </div>
            </div>
        </div>

        <div v-else-if="events.length === 0" class="empty">
            No upcoming events found.
        </div>

        <div v-else class="poster-grid">
            <div class="poster" v-for="event in events" :key="event.event_id">
                <div class="poster-image">
                    <img
                        :src="
                            event.image_url ||
                            'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1200&q=80'
                        "
                        alt="event poster"
                    />
                </div>

                <div class="poster-content">
                    <div class="poster-top">
                        <span class="tag">EVENT</span>
                        <span class="date">{{ formatDate(event.date) }}</span>
                    </div>

                    <h3 class="title">{{ event.event_name }}</h3>
                    <p class="location">📍 {{ event.location }}</p>
                    <p class="desc">{{ event.description }}</p>

                    <div class="actions">
                        <button class="btn edit" @click="openEdit(event)">
                            Edit
                        </button>
                        <button
                            class="btn delete"
                            @click="confirmDelete(event)"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CREATE EVENT MODAL -->
        <CreateEventModal v-model="showCreate" @created="fetchEvents" />

        <!-- EDIT EVENT MODAL -->
        <EditEventModal
            v-model="showEdit"
            :event-id="selectedEventId"
            @updated="fetchEvents"
        />

        <!-- DELETE EVENT MODAL -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showDeleteModal"
                    class="modal-overlay"
                    @click.self="showDeleteModal = false"
                >
                    <div class="modal">
                        <h2>Delete Event</h2>
                        <p>
                            Are you sure you want to delete
                            <b>"{{ selectedEvent?.event_name }}"</b>?
                        </p>

                        <div class="modal-actions">
                            <button class="delete-btn" @click="handleDelete">
                                {{ isDeleting ? 'Deleting...' : 'Delete' }}
                            </button>
                            <button
                                class="cancel-btn"
                                @click="showDeleteModal = false"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get, del } from '@/services/apiService';
import CreateEventModal from '@/components/CreateEventModal.vue';
import EditEventModal from '@/components/EditEventModal.vue';

const events = ref([]);
const isLoading = ref(true);
const isDeleting = ref(false);

const showCreate = ref(false);
const showDeleteModal = ref(false);
const selectedEvent = ref(null);

const showEdit = ref(false);
const selectedEventId = ref(null);

const openEdit = (event) => {
    selectedEventId.value = event.event_id;
    showEdit.value = true;
};

const fetchEvents = async () => {
    isLoading.value = true;
    try {
        const res = await get('/org/events');
        events.value = res || [];
    } catch (error) {
        console.error('Fetch failed:', error);
    } finally {
        isLoading.value = false;
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString();
};

const confirmDelete = (event) => {
    selectedEvent.value = event;
    showDeleteModal.value = true;
};

const handleDelete = async () => {
    if (!selectedEvent.value) return;
    isDeleting.value = true;
    try {
        await del(`/org/events/${selectedEvent.value.event_id}`);
        showDeleteModal.value = false;
        await fetchEvents();
    } catch (error) {
        console.error('Delete failed:', error);
    } finally {
        isDeleting.value = false;
        selectedEvent.value = null;
    }
};

onMounted(fetchEvents);
</script>

<style scoped>
.page-container {
    padding-left: 20px;
    padding-top: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-top: 20px;
}

h1 {
    font-size: 26px;
    font-weight: 800;
    margin: 0;
    color: #7f1d1d;
}
.subtext {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
}

.create-btn {
    padding: 10px 14px;
    background: #7f1d1d;
    color: white;
    border: 2px solid #7f1d1d;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.create-btn:hover {
    background: #064e3b;
    color: gold;
    border: 2px solid gold;
}

/* GRID - MATCHES STUDENT VIEW */
.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

/* POSTER - MATCHES STUDENT VIEW */
.poster {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    transition: 0.25s ease;
    display: flex;
    flex-direction: column;
}

.poster:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.12);
}

/* IMAGE - MATCHES STUDENT VIEW (180px) */
.poster-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.poster-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* CONTENT - MATCHES STUDENT VIEW */
.poster-content {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.poster-top {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.tag {
    background: #7f1d1d;
    color: white;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.date {
    font-size: 12px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
}

/* TYPOGRAPHY */
.title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}

.location {
    font-size: 13px;
    color: #4b5563;
    margin: 4px 0;
}

.desc {
    font-size: 13px;
    color: #6b7280;
    margin-top: 8px;
    line-height: 1.5;
    margin-bottom: 16px;
}

/* ACTIONS (ORG SPECIFIC) */
.actions {
    display: flex;
    gap: 10px;
    margin-top: auto;
}

.btn {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}

.edit:hover {
    background: #064e3b;
    color: gold;
    border: 2px solid gold;
}

.delete:hover {
    background: #7f1d1d;
    color: white;
    border: 2px solid gold;
}

/* MODALS */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal {
    background: white;
    padding: 26px;
    border-radius: 14px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.delete-btn,
.cancel-btn {
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    border: 2px solid #7f1d1d;
    transition: 0.2s;
}

.delete-btn {
    background: white;
    color: #7f1d1d;
    border-color: #7f1d1d;
}
.delete-btn:hover {
    background: #7f1d1d;
    color: white;
}

.cancel-btn {
    background: #f3f4f6;
    border: 2px solid #f3f4f6;
    color: #4b5563;
}
.cancel-btn:hover {
    border-color: gold;
}

/* EMPTY STATE */
.empty {
    padding: 40px;
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    text-align: center;
    color: #6b7280;
}

/* SKELETON */
.skeleton-image {
    height: 180px;
    background: #f3f4f6;
    border-radius: 12px;
}
.skeleton.title {
    height: 20px;
    width: 60%;
    background: #f3f4f6;
    margin-top: 12px;
}
.skeleton.text {
    height: 14px;
    width: 90%;
    background: #f3f4f6;
    margin-top: 8px;
}

/* TRANSITIONS */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
