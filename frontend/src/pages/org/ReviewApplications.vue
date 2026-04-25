<template>
    <div class="page">
        <h2>Review Applications</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Committee</th>
                    <th>Cover Letter</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="app in applications" :key="app.id">
                    <td>{{ app.name }}</td>
                    <td>{{ app.committee }}</td>
                    <td>{{ app.cover }}</td>
                    <td>
                        <button class="approve">Approve</button>
                        <button class="reject">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const applications = [
    {
        id: 1,
        name: 'Maria',
        committee: 'Marketing',
        cover: 'I want to join...',
    },
];
</script>
