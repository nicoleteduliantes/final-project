<template>
    <div>
        <h1>Current Memberships</h1>

        <table>
            <tr>
                <th>Organization</th>
            </tr>

            <tr v-for="org in orgs" :key="org.id">
                <td>{{ org.name }}</td>
            </tr>
        </table>
    </div>
</template>

<script setup>
const orgs = [{ id: 1, name: 'AWS Cloud Club' }];
</script>
