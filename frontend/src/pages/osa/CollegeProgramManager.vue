<template>
    <div class="page">
        <div class="header-row">
            <h1>Colleges and Degree Programs</h1>
            <div class="controls">
                <select v-model="selectedCollege" class="college-select">
                    <option value="">All Colleges</option>
                    <option
                        v-for="college in uniqueColleges"
                        :key="college"
                        :value="college"
                    >
                        {{ college }}
                    </option>
                </select>
                <div class="search-box">
                    <input v-model="search" placeholder="Search programs..." />
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Degree Program</th>
                        <th>College / Department</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="prog in filteredPrograms" :key="prog.id">
                        <td class="name-column">{{ prog.degprog_name }}</td>
                        <td>
                            <span class="college-tag">
                                {{
                                    prog.college?.college_name ||
                                    prog.college ||
                                    'N/A'
                                }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="filteredPrograms.length === 0" class="empty-msg">
                No degree programs found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { get } from '@/services/apiService';

const programs = ref([]);
const search = ref('');
const selectedCollege = ref('');

const fetchPrograms = async () => {
    try {
        const res = await get('/osa/degree-programs');
        programs.value = res.data ?? res;
    } catch (err) {
        console.error('Fetch failed:', err);
    }
};

const getCollegeName = (p) => p.college?.college_name || p.college || 'N/A';

const uniqueColleges = computed(() => {
    const names = programs.value.map((p) => getCollegeName(p));
    return [...new Set(names)].filter((name) => name !== 'N/A');
});

const filteredPrograms = computed(() => {
    return programs.value.filter((p) => {
        const collegeName = getCollegeName(p).toLowerCase();
        const progName = p.degprog_name.toLowerCase();
        const query = search.value.toLowerCase();

        const matchesSearch =
            progName.includes(query) || collegeName.includes(query);
        const matchesCollege =
            !selectedCollege.value ||
            getCollegeName(p) === selectedCollege.value;

        return matchesSearch && matchesCollege;
    });
});

onMounted(fetchPrograms);
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

.header-row h1 {
    font-size: 22px;
    font-weight: 600;
}

.controls {
    display: flex;
    gap: 10px;
}

.search-box input,
.college-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.search-box input {
    width: 200px;
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
    padding: 12px 15px;
    text-align: left;
    font-size: 14px;
}

tbody tr {
    border-top: 1px solid #eee;
}

tbody tr:hover {
    background-color: #f9fafb;
}

.name-column {
    font-weight: 600;
    color: #111827;
}

.college-tag {
    color: #4b5563;
}

.empty-msg {
    text-align: center;
    padding: 30px;
    color: #6b7280;
}
</style>
