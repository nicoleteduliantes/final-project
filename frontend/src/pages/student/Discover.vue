<template>
    <div class="page">
        <h1>Discover</h1>

        <h2>Organizations</h2>

        <div class="grid">
            <div class="card" v-for="org in orgs" :key="org.id">
                <h3>{{ org.name }}</h3>

                <p class="muted">Student Organization</p>

                <div class="actions">
                    <RouterLink :to="'/org/' + org.id" class="btn">
                        View Profile
                    </RouterLink>

                    <RouterLink :to="'/apply/' + org.id" class="btn apply">
                        Apply
                    </RouterLink>
                </div>
            </div>
        </div>

        <h2>Events</h2>

        <div class="grid">
            <div class="card" v-for="event in events" :key="event.id">
                <h3>{{ event.name }}</h3>

                <RouterLink :to="'/event/' + event.id" class="btn">
                    View Event
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
const orgs = [
    { id: 1, name: 'AWS Cloud Club' },
    { id: 2, name: 'GDSC UP' },
];

const events = [{ id: 1, name: 'Tech Talk' }];
</script>

<style scoped>
.page {
    padding: 20px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}

.card {
    background: white;
    padding: 16px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.actions {
    display: flex;
    gap: 8px;
}

.muted {
    color: #6b7280;
    font-size: 14px;
}

.btn {
    background: #2563eb;
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    width: fit-content;
}

.apply {
    background: #059669;
}

.apply:hover {
    background: #047857;
}

.btn:hover {
    background: #1d4ed8;
}
</style>
