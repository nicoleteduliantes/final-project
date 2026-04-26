<template>
    <div class="page">
        <h1>Discover</h1>

        <h2>Organizations</h2>

        <div class="grid">
            <div class="card" v-for="org in orgs" :key="org.id">
                <h3>{{ org.name }}</h3>

                <div class="actions">
                    <RouterLink :to="'/org/' + org.id" class="link">
                        View
                    </RouterLink>

                    <RouterLink :to="'/apply/' + org.id" class="apply">
                        Apply
                    </RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const orgs = [{ id: 1, name: 'AWS Cloud Club' }];
</script>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.link {
    color: #facc15;
    text-decoration: none;
}

.apply {
    color: white;
    background: #064e3b;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
}
</style>
