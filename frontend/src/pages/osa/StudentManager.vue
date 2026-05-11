<template>
    <div class="page">
        <div class="header-row">
            <h1>Student Directory</h1>
            <div class="search-box">
                <input
                    v-model="searchQuery"
                    placeholder="Search by name or ID..."
                />
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>UP Email</th>
                        <th>Degree Program</th>
                        <th>Organizations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="student in filteredStudents"
                        :key="student.student_id"
                    >
                        <td class="id-column">{{ student.student_id }}</td>
                        <td class="name-column">
                            {{ student.first_name }} {{ student.last_name }}
                        </td>
                        <td>{{ student.up_email }}</td>
                        <td>
                            <span class="prog-tag">{{
                                student.degree_program?.degprog_name || 'N/A'
                            }}</span>
                        </td>
                        <td>
                            <div class="org-list">
                                <template v-if="student.memberships?.length">
                                    <span
                                        v-for="m in student.memberships"
                                        :key="m.membership_id"
                                        class="org-badge"
                                    >
                                        {{ m.organization?.org_name }}
                                    </span>
                                </template>
                                <span v-else class="text-muted">None</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="filteredStudents.length === 0" class="empty-msg">
                No student records found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get } from '@/services/apiService';

const students = ref([]);
const searchQuery = ref('');

onMounted(async () => {
    try {
        const res = await get('/osa/students');
        students.value = res.data ?? res;
    } catch (err) {
        console.error('Failed to fetch student directory', err);
    }
});

const filteredStudents = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    if (!query) return students.value;

    return students.value.filter((s) => {
        const full_name = `${s.first_name} ${s.last_name}`.toLowerCase();
        return (
            full_name.includes(query) ||
            s.student_id.toString().includes(query) ||
            s.up_email.toLowerCase().includes(query)
        );
    });
});
</script>

<style scoped>
.page {
    padding: clamp(20px, 10vw, 50px);
    min-height: 100vh;
    width: 100%;
}

.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.search-box input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    width: 250px;
}

.table-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f3f4f6;
}

th,
td {
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

tbody tr {
    border-top: 1px solid #eee;
}

.org-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    max-width: 400px;
}

.org-badge {
    background: #7f1d1d;
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

.prog-tag {
    color: #4b5563;
}

.text-muted {
    color: #9ca3af;
    font-style: italic;
}

.empty-msg {
    text-align: center;
    padding: 20px;
    color: #6b7280;
}
</style>
