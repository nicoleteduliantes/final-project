<template>
    <div class="page">
        <!-- HEADER -->
        <div class="header">
            <h1>Office of Student Affairs</h1>
            <p class="subtext">
                Manage announcements and monitor campus events
            </p>
        </div>

        <!-- CREATE ANNOUNCEMENT -->
        <div class="announcement-section">
            <div class="section-header" @click="toggleCreate">
                <h2>
                    Post New Announcement
                    <span class="arrow" :class="{ 'is-active': showCreate }">
                        ▼
                    </span>
                </h2>
            </div>

            <Transition name="expand">
                <div v-if="showCreate" class="announcement-form">
                    <div class="input-group">
                        <label>Title</label>
                        <input
                            v-model.trim="newAnnouncement.title"
                            placeholder="Enter announcement title"
                        />
                    </div>

                    <div class="input-group">
                        <label>Content</label>
                        <textarea
                            v-model.trim="newAnnouncement.content"
                            rows="4"
                            placeholder="Write announcement details..."
                        ></textarea>
                    </div>

                    <button
                        class="primary-btn"
                        @click="postAnnouncement"
                        :disabled="isPosting"
                    >
                        {{
                            isPosting ? 'Publishing...' : 'Publish Announcement'
                        }}
                    </button>
                </div>
            </Transition>
        </div>

        <!-- MY ANNOUNCEMENTS -->
        <div class="my-announcements">
            <div class="list-header">
                <h2>My Announcements</h2>
                <span class="count">{{ announcements.length }} total</span>
            </div>

            <div
                v-if="!announcements || announcements.length === 0"
                class="empty"
            >
                <div class="empty-icon">📢</div>
                <p>No announcements yet.</p>
            </div>

            <div v-else class="announcement-list">
                <div
                    v-for="ann in announcements"
                    :key="ann.announcement_id"
                    class="announcement-item"
                >
                    <div class="info">
                        <div class="item-header">
                            <h3>{{ ann.title }}</h3>
                            <small>{{ formatDate(ann.date_posted) }}</small>
                        </div>
                        <p>{{ ann.content }}</p>
                    </div>

                    <div class="actions">
                        <button class="btn edit" @click="openEdit(ann)">
                            Edit
                        </button>
                        <button class="btn delete" @click="confirmDelete(ann)">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- UPCOMING EVENTS -->
        <section class="section">
            <h2 class="section-title">Upcoming Events</h2>

            <div v-if="events.length === 0" class="empty">
                No upcoming events found.
            </div>

            <div v-else class="poster-grid">
                <div
                    class="poster"
                    v-for="event in events"
                    :key="event.event_id"
                >
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
                            <span class="tag">{{ event.event_host }}</span>
                            <span class="date">{{ event.date }}</span>
                        </div>

                        <h3 class="title">
                            {{ event.event_name }}
                        </h3>

                        <p class="location">📍 {{ event.location }}</p>

                        <p class="desc">
                            {{ event.description }}
                        </p>

                        <div class="actions">
                            <RouterLink
                                :to="'/event/' + event.event_id"
                                class="btn link"
                            >
                                View Event
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- EDIT MODAL -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showModal"
                    class="modal-overlay"
                    @click.self="closeModal"
                >
                    <div class="modal">
                        <h2>Edit Announcement</h2>

                        <div class="input-group">
                            <label>Title</label>
                            <input v-model="editForm.title" />
                        </div>

                        <div class="input-group">
                            <label>Content</label>
                            <textarea
                                v-model="editForm.content"
                                rows="5"
                            ></textarea>
                        </div>

                        <div class="modal-actions">
                            <button
                                class="primary-btn"
                                @click="updateAnnouncement"
                            >
                                Save Changes
                            </button>

                            <button class="cancel-btn" @click="closeModal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- DELETE MODAL -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showDeleteModal"
                    class="modal-overlay"
                    @click.self="showDeleteModal = false"
                >
                    <div class="modal">
                        <h2>Delete Announcement</h2>

                        <p>
                            Are you sure you want to delete
                            <b>"{{ deleteTarget?.title }}"</b>?
                        </p>

                        <div class="modal-actions">
                            <button
                                class="delete-confirm-btn"
                                @click="deleteAnnouncement"
                            >
                                Delete
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

        <!-- TOAST -->
        <Transition name="slide">
            <div v-if="toast.show" class="toast">
                {{ toast.message }}
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { get, post, put, del } from '@/services/apiService';

/* STATE */
const showCreate = ref(false);
const isPosting = ref(false);

const newAnnouncement = ref({
    title: '',
    content: '',
});

const announcements = ref([]);
const events = ref([]);

const showModal = ref(false);
const showDeleteModal = ref(false);

const editForm = ref({
    id: null,
    title: '',
    content: '',
});

const deleteTarget = ref(null);

/* TOAST */
const toast = reactive({
    show: false,
    message: '',
});

const showToast = (msg) => {
    toast.message = msg;
    toast.show = true;

    setTimeout(() => {
        toast.show = false;
    }, 2500);
};

/* TOGGLE CREATE */
const toggleCreate = () => {
    showCreate.value = !showCreate.value;
};

/* FETCH ONLY OSA ANNOUNCEMENTS */
const fetchMyAnnouncements = async () => {
    try {
        const res = await get('/osa/announcements/my');

        let data = [];

        if (Array.isArray(res)) {
            data = res;
        } else if (Array.isArray(res?.data)) {
            data = res.data;
        } else if (Array.isArray(res?.data?.data)) {
            data = res.data.data;
        }

        announcements.value = data;
    } catch (err) {
        console.error('FETCH ERROR:', err);
        announcements.value = [];
        showToast('Failed to load announcements');
    }
};

/* POST */
const postAnnouncement = async () => {
    if (
        !newAnnouncement.value.title.trim() ||
        !newAnnouncement.value.content.trim()
    ) {
        showToast('Please fill in all fields.');
        return;
    }

    isPosting.value = true;

    try {
        await post('/osa/announcements', newAnnouncement.value);

        newAnnouncement.value = {
            title: '',
            content: '',
        };

        showCreate.value = false;

        await fetchMyAnnouncements();

        showToast('Announcement posted!');
    } catch (err) {
        console.error(err.response?.data || err);
        showToast(err.response?.data?.message || 'Failed to post announcement');
    } finally {
        isPosting.value = false;
    }
};

/* EDIT */
const openEdit = (ann) => {
    editForm.value = {
        id: ann.announcement_id,
        title: ann.title,
        content: ann.content,
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const updateAnnouncement = async () => {
    try {
        await put(`/osa/announcements/${editForm.value.id}`, editForm.value);

        closeModal();
        await fetchMyAnnouncements();

        showToast('Announcement updated!');
    } catch (err) {
        console.error(err);
        showToast('Update failed');
    }
};

/* DELETE */
const confirmDelete = (ann) => {
    deleteTarget.value = ann;
    showDeleteModal.value = true;
};

const deleteAnnouncement = async () => {
    try {
        await del(`/osa/announcements/${deleteTarget.value.announcement_id}`);

        showDeleteModal.value = false;
        await fetchMyAnnouncements();

        showToast('Announcement deleted!');
    } catch (err) {
        console.error(err);
        showToast('Delete failed');
    }
};

/* FORMAT DATE */
const formatDate = (date) =>
    date ? new Date(date).toLocaleDateString() : 'Just now';

/* INIT */
onMounted(fetchMyAnnouncements);
</script>

<style scoped>
.page {
    padding-left: 20px;
    padding-top: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* HEADER */
.header {
    margin-bottom: 30px;
    padding-top: 20px;
}

h1 {
    font-size: 28px;
    font-weight: 800;
    color: #7f1d1d;
    margin: 0;
}

.subtext {
    font-size: 15px;
    color: #6b7280;
    margin-top: 6px;
}

/* SHARED CONTAINERS */
.announcement-section,
.my-announcements {
    background: white;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
}

/* SECTION HEADERS */
.section-header {
    cursor: pointer;
    display: flex;
    align-items: center;
}

.section-header h2 {
    font-size: 18px;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.arrow {
    font-size: 20px;
    transition: transform 0.3s ease;
    color: #7f1d1d;
}

.arrow.is-active {
    transform: rotate(180deg);
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.list-header h2 {
    font-size: 18px;
    font-weight: 800;
    margin: 0;
}

.count {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 999px;
}

/* FORM */
.announcement-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding-top: 20px;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.input-group label {
    font-size: 13px;
    font-weight: 700;
    color: #374151;
}

input,
textarea {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    transition: 0.2s;
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #7f1d1d;
    box-shadow: 0 0 0 3px rgba(127, 29, 29, 0.1);
}

/* ANNOUNCEMENT LIST */
.announcement-item {
    padding: 20px 0;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: all 0.25s ease;
}

.announcement-item:hover {
    transform: translateY(-2px);
}

.announcement-item:last-child {
    border-bottom: none;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.item-header h3 {
    font-size: 17px;
    font-weight: 700;
    margin: 0;
    color: #111827;
}

.item-header small {
    color: #9ca3af;
    font-weight: 500;
}

.announcement-item p {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.6;
    margin: 0;
}

/* BUTTONS */
.btn,
.primary-btn,
.cancel-btn,
.delete-confirm-btn,
.edit,
.delete {
    padding: 10px 16px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s ease;
    font-size: 13px;
    border: 2px solid #cba9a9;
}

.edit,
.delete {
    background: white;
    color: #7f1d1d;
}

.edit:hover,
.primary-btn:hover {
    background: #064e3b;
    color: gold;
    border-color: gold;
    transform: translateY(-2px);
}

.delete:hover {
    background: #7f1d1d;
    color: white;
    border-color: gold;
    transform: translateY(-2px);
}

.primary-btn {
    background: #7f1d1d;
    color: white;
    border-color: #7f1d1d;
}

.cancel-btn,
.delete-confirm-btn {
    flex: 1;
    padding: 12px;
}

.cancel-btn {
    background: #f3f4f6;
    border: 2px solid #f3f4f6;
    color: #4b5563;
}

.cancel-btn:hover {
    border-color: gold;
}

.delete-confirm-btn {
    background: white;
    color: #7f1d1d;
    border: 2px solid #7f1d1d;
}

.delete-confirm-btn:hover {
    color: white;
    background: #7f1d1d;
}

.actions {
    display: flex;
    gap: 8px;
}

/* EVENTS */
.section {
    margin-top: 32px;
}

.section-title {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 16px;
}

.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 18px;
}

.poster {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: 0.25s ease;
}

.poster:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08);
}

.poster-image {
    height: 160px;
}

.poster-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.poster-content {
    padding: 14px;
}

.poster-top {
    display: flex;
    justify-content: space-between;
}

.tag {
    background: #7f1d1d;
    color: white;
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.date {
    font-size: 12px;
    color: #6b7280;
}

.title {
    margin: 10px 0 4px;
    font-size: 18px;
    font-weight: 800;
}

.location,
.desc {
    font-size: 13px;
    color: #4b5563;
}

/* EMPTY */
.empty {
    padding: 40px;
    text-align: center;
    border: 2px dashed #e5e7eb;
    border-radius: 12px;
}

.empty-icon {
    font-size: 32px;
    margin-bottom: 10px;
}

/* MODALS */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal {
    background: white;
    padding: 30px;
    border-radius: 16px;
    width: 90%;
    max-width: 450px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

/* TOAST */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #7f1d1d;
    color: white;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 700;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    z-index: 2000;
}

/* ANIMATIONS */
.expand-enter-active,
.expand-leave-active {
    transition: all 0.35s ease;
    max-height: 500px;
    overflow: hidden;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px);
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}

.slide-enter-from {
    opacity: 0;
    transform: translateX(40px);
}

.slide-leave-to {
    opacity: 0;
    transform: translateX(40px);
}
</style>
