<template>
    <div class="page">
        <h2>Review Applications</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Committee</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="app in applications" :key="app.membership_id">
                    <td>{{ app.student_name }}</td>
                    <td>{{ app.applied_committee }}</td>
                    <td>{{ app.cover_letter }}</td>
                    <td>
                        <button class="view" @click="openReview(app)">View Details</button>
                    </td>
                </tr>
            </tbody>
        </table>

            <div v-if="selectedApp" class="modal-overlay">
      <div class="modal-content">
        <h3>Application Review: {{ selectedApp.student_name }}</h3>
        <hr>
        <p><strong>Applied Committee:</strong> {{ selectedApp.applied_committee }}</p>
        <p><strong>Cover Letter:</strong></p>
        <div class="text-box">{{ selectedApp.cover_letter }}</div>
        
        <p><strong>Skills:</strong> {{ selectedApp.raw.application_detail?.skills || 'None listed' }}</p>

        <div class="modal-actions">
          <button class="approve" @click="handleUpdate('Accepted', 'Member')">Approve</button>
          <button class="reject" @click="handleUpdate('Rejected', 'Applicant')">Reject</button>
          <button class="close" @click="selectedApp = null">Close</button>
        </div>
      </div>
    </div>
    </div>
</template>

<script setup>

import {ref, onMounted} from 'vue';
import {get, put} from '@/services/apiService';

const applications = ref ([]);
const selectedApp = ref (null);

const fetchApplications = async () => 
{
    try {
    const response = await get('/org/applications');

    applications.value = response.map(item => ({
        membership_id: item.membership_id,
        student_name: `${item.student.first_name} ${item.student.last_name}`,
        applied_committee: item.application_detail?.applied_committee || 'N/A',
        cover_letter: item.application_detail?.cover_letter || 'No cover letter provided',
        raw: item 
    }));
    } catch (error) {
        console.error("Fetch error:", error);
    }
};

const openReview = (app) => {
    selectedApp.value = app;
};

const handleUpdate = async (status, position) => {
    const id = selectedApp.value.membership_id;
    if (!confirm(`Are you sure you want to ${status} this applicant?`)) return;

    try {
        await put(`/org/applications/${id}`, { status, position });
        alert(`Application ${status}!`);
        selectedApp.value = null; // Close modal
        await fetchApplications(); // Refresh list
    } catch (error) {
        alert("Update failed.");
    }
};

onMounted(fetchApplications);
</script>
