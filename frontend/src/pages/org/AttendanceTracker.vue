<template>
    <div class="page">
        <h2>Attendance Tracker</h2>

        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Event</th>
                    <th>Role</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="a in attendance" :key="a.id">
                    <td>{{ a.name }}</td>
                    <td>{{ a.event }}</td>
                    <td>{{ a.role }}</td>
                    <td>{{ a.date }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const attendance = [
    {
        id: 1,
        name: 'Juan Dela Cruz',
        event: 'Hackathon',
        role: 'Participant',
        date: 'March 10',
    },
];
</script>
