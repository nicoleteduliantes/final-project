<template>
    <div class="page">
        <div class="header">
            <h1>Student Manager</h1>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Degree Program</th>
                        <th>Organizations</th>
                        <th>Events Joined</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="student in students" :key="student.id">
                        <td>{{ student.name }}</td>
                        <td>{{ student.degree_program }}</td>

                        <td>
                            {{ formatList(student.organizations) }}
                        </td>

                        <td>
                            {{ formatList(student.events) }}
                        </td>
                    </tr>

                    <tr v-if="students.length === 0">
                        <td colspan="4" class="empty">No students found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { get } from '@/services/apiService';

const students = ref([]);

const fetchStudents = async () => {
    try {
        const res = await get('/students');
        console.log('STUDENT RESPONSE:', res);

        students.value = res.data ?? res;
    } catch (err) {
        console.error('Failed to fetch students:', err);
    }
};

const formatList = (value) => {
    if (!value) return 'None';

    // if array → join
    if (Array.isArray(value)) {
        return value.join(', ');
    }

    // if string → return as is
    return value;
};

onMounted(fetchStudents);
</script>

<style scoped>
.page {
    padding: 20px;
}

.header {
    margin-bottom: 15px;
}

.header h1 {
    font-size: 22px;
    font-weight: 600;
}

.table-card {
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

.empty {
    text-align: center;
    padding: 20px;
    color: #6b7280;
}
</style>
