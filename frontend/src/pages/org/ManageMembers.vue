<template>
    <div class="page">
        <h2>Manage Members</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Committee</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="m in members" :key="m.membership_id">
                    <td>{{ m.student_name }}</td>
                    <td>{{ m.email }}</td>
                    <td>{{ m.applied_committee }}</td>
                    <td>
                        <button class="danger" @click = "removeMember(m.membership_id)">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get, del } from '@/services/apiService'; // Assuming 'del' is your DELETE method

const members = ref([]);

const fetchMembers = async () => {
    try {
        const response = await get('/org/members');
        members.value = response.map(m => ({
            membership_id: m.membership_id,
            student_name: `${m.student.first_name} ${m.student.last_name}`,
            email: m.student.up_email, 
            applied_committee: m.application_detail?.applied_committee || 'General',
        }));
    } catch (error) {
        console.error("Error loading members:", error);
    }
};

const removeMember = async (id) => {
    
    if (confirm("Are you sure you want to remove this member?")) {
        try {
            await del(`/org/members/${id}`);
            alert('Member removed.');
            
            await fetchMembers(); 
        } catch (error) {
            alert('Failed to remove member.');
        }
    }
};

onMounted(fetchMembers);
</script>
