<template>
    <div class="page">
        <div class="header">
            <h1>Organization Dashboard</h1>

            <p class="org-badge">
                <span v-if="organization">
                    {{ organization.org_name }}
                </span>

                <span v-else> Loading organization... </span>

                <span v-if="organization" class="verified">✔</span>
            </p>
        </div>

        <!-- POST NEW ANNOUNCEMENT -->
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
                            placeholder="e.g. Membership applications are now open!"
                        />
                    </div>

                    <div class="input-group">
                        <label>Content</label>
                        <textarea
                            v-model.trim="newAnnouncement.content"
                            placeholder="Write your announcement details here..."
                            rows="4"
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
            <div class="section-header" @click="toggleMyAnnouncements">
                <h2>
                    My Announcements
                    <span
                        class="arrow"
                        :class="{ 'is-active': showMyAnnouncements }"
                    >
                        ▼
                    </span>
                </h2>

                <span class="count">{{ myAnnouncements.length }} total</span>
            </div>

            <Transition name="expand">
                <div v-if="showMyAnnouncements">
                    <div v-if="myAnnouncements.length === 0" class="empty">
                        <div class="empty-icon">📢</div>
                        <p>
                            No announcements yet. Share something with your
                            organization!
                        </p>
                    </div>

                    <div v-else class="announcement-stack">
                        <div
                            class="poster announcement-card"
                            v-for="ann in myAnnouncements"
                            :key="ann.announcement_id"
                        >
                            <div class="poster-content">
                                <div class="poster-top">
                                    <span class="tag announcement-tag">{{
                                        organization?.org_name || 'My Post'
                                    }}</span>
                                    <span class="date">
                                        {{ formatDate(ann.date_posted) }}
                                    </span>
                                </div>

                                <h3 class="title">{{ ann.title }}</h3>

                                <p class="desc">{{ ann.content }}</p>

                                <div class="actions" style="margin-top: 12px">
                                    <button
                                        class="btn edit"
                                        @click="openEdit(ann)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn delete"
                                        @click="confirmDelete(ann)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>

        <!-- CAMPUS-WIDE ANNOUNCEMENTS -->
        <section class="section">
            <h2 class="section-title">Campus-wide Announcements</h2>

            <div v-if="allAnnouncements.length === 0" class="empty">
                <p>No campus announcements found.</p>
            </div>

            <div v-else class="announcement-stack">
                <div
                    class="poster announcement-card"
                    v-for="ann in allAnnouncements"
                    :key="ann.announcement_id"
                >
                    <div class="poster-content">
                        <div class="poster-top">
                            <span class="tag announcement-tag"
                                >ANNOUNCEMENT</span
                            >
                            <span class="date">
                                {{ formatDate(ann.date_posted) }}
                            </span>
                        </div>

                        <h3 class="title">{{ ann.title }}</h3>

                        <p class="posted-by">
                            Posted by:
                            <b>
                                {{
                                    ann.osa_id
                                        ? 'Office of the Student Affairs'
                                        : ann.organization?.org_name ||
                                          'Organization'
                                }}
                            </b>
                        </p>

                        <p class="desc">{{ ann.content }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
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
                            <span class="date">{{
                                formatDate(event.date)
                            }}</span>
                        </div>

                        <h3 class="title">{{ event.event_name }}</h3>

                        <p class="location">📍 {{ event.location }}</p>

                        <p class="desc">{{ event.description }}</p>
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
import { ref, onMounted, reactive } from 'vue';
import { get, post, put, del } from '@/services/apiService';

const showCreate = ref(false);
const showMyAnnouncements = ref(false);
const toggleMyAnnouncements = () =>
    (showMyAnnouncements.value = !showMyAnnouncements.value);
const isPosting = ref(false);
const toggleCreate = () => (showCreate.value = !showCreate.value);

const newAnnouncement = ref({ title: '', content: '' });
const myAnnouncements = ref([]);
const allAnnouncements = ref([]);
const events = ref([]); // New State

const showModal = ref(false);
const showDeleteModal = ref(false);
const deleteTarget = ref(null);
const editForm = ref({ id: null, title: '', content: '' });
const organization = ref(null);

const fetchOrganization = async () => {
    try {
        const res = await get('/org/profile');

        const data = res?.data?.data ?? res?.data ?? res ?? null;

        organization.value = data;
    } catch (err) {
        console.error('Failed to fetch org:', err);
    }
};

const toast = reactive({ show: false, message: '' });
const showToast = (msg) => {
    toast.message = msg;
    toast.show = true;
    setTimeout(() => (toast.show = false), 2500);
};

const formatDate = (date) =>
    date ? new Date(date).toLocaleDateString() : 'Just now';

const fetchMyAnnouncements = async () => {
    try {
        const res = await get('/announcements/my');
        const data = res?.data?.data ?? res?.data ?? res ?? [];
        myAnnouncements.value = Array.isArray(data) ? data : [];
    } catch (error) {
        console.error(error);
    }
};

const fetchAllAnnouncements = async () => {
    try {
        const res = await get('/announcements');
        const data = res?.data?.data ?? res?.data ?? res ?? [];
        allAnnouncements.value = Array.isArray(data) ? data : [];
    } catch (error) {
        console.error(error);
    }
};

const fetchEvents = async () => {
    try {
        const res = await get('/events');
        const data = res?.data?.data ?? res?.data ?? res ?? [];
        events.value = Array.isArray(data) ? data : [];
    } catch (error) {
        console.error(error);
    }
};

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
        await post('/announcements', newAnnouncement.value);
        newAnnouncement.value = { title: '', content: '' };
        showCreate.value = false;
        await Promise.all([fetchMyAnnouncements(), fetchAllAnnouncements()]);
        showToast('Announcement posted!');
    } catch (error) {
        showToast('Failed to post.');
    } finally {
        isPosting.value = false;
    }
};

const openEdit = (ann) => {
    editForm.value = {
        id: ann.announcement_id,
        title: ann.title,
        content: ann.content,
    };
    showModal.value = true;
};

const closeModal = () => (showModal.value = false);

const updateAnnouncement = async () => {
    try {
        await put(`/announcements/${editForm.value.id}`, editForm.value);
        closeModal();
        await fetchMyAnnouncements();
        showToast('Announcement updated.');
    } catch (error) {
        showToast('Update failed.');
    }
};

const confirmDelete = (ann) => {
    deleteTarget.value = ann;
    showDeleteModal.value = true;
};

const deleteAnnouncement = async () => {
    try {
        await del(`/announcements/${deleteTarget.value.announcement_id}`);
        showDeleteModal.value = false;
        await fetchMyAnnouncements();
        showToast('Announcement deleted.');
    } catch (error) {
        showToast('Delete failed.');
    }
};

onMounted(() => {
    fetchMyAnnouncements();
    fetchAllAnnouncements();
    fetchEvents();
    fetchOrganization();
});
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
    padding-top: 20px;
}
h1 {
    font-weight: 800;
    color: #7f1d1d;
    margin: 0;
}
.org-badge {
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 6px 12px;
    border-radius: 999px;

    background: #064e3b;
    color: gold;

    font-size: 13px;
    font-weight: 700;

    border: 2px solid gold;
}

.org-badge .verified {
    font-size: 12px;
    color: gold;
    margin-left: 2px;
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
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 10px;
}

/* SECTION HEADERS */
.section-header {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
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
    width: 40px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 999px;
}

/* CAMPUS ANNOUNCEMENTS */
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

.announcement-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.announcement-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    transition: 0.2s ease;
}

.announcement-card:hover {
    border-left: 6px solid #7f1d1d;
}

.poster-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.announcement-tag {
    background: #064e3b !important;
    color: white;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.date {
    font-size: 12px;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
}

.title {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 8px;
    color: #7f1d1d;
}

.posted-by {
    font-size: 13px;
    color: #7f1d1d;
    margin-bottom: 10px;
}

.desc {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.6;
}

/* FORM & INPUTS */
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

/* LIST ITEMS */
.announcement-item {
    padding: 20px 0;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    flex-direction: column;
    gap: 12px;
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

/* EVENTS */
.empty {
    padding: 20px;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
    text-align: center;
    color: #6b7280;
}

.poster-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

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

/* CONTENT */
.poster-content {
    padding: 16px;
    flex: 1;
}

/* TOP */
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
    transition: 0.2s;
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
}

.delete:hover {
    background: #7f1d1d;
    color: white;
    border-color: gold;
}

.cancel-btn,
.delete-confirm-btn {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
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
.actions .btn {
    flex: 0 1 auto;
}

/* MODAL STYLES */
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

.modal-header h2 {
    color: #111827;
    margin-top: 0;
}
.modal-header p {
    color: #6b7280;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

/* EMPTY STATE */
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
.empty p {
    color: #6b7280;
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
    transition: all 0.3s ease;
    max-height: 500px;
    overflow: hidden;
}
.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    padding-top: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(50px);
    opacity: 0;
}
</style>
