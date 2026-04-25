<template>
    <div class="page">
        <h2>Manage Members</h2>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Committee</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="m in members" :key="m.id">
                    <td>{{ m.name }}</td>
                    <td>{{ m.email }}</td>
                    <td>{{ m.committee }}</td>
                    <td>{{ m.joined }}</td>
                    <td>
                        <button class="danger">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
const members = [
    {
        id: 1,
        name: 'Juan Dela Cruz',
        email: 'juan@email.com',
        committee: 'Tech',
        joined: 'Jan 2026',
    },
];
</script>
