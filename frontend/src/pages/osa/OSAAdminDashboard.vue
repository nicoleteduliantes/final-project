<template>
    <div class="page">
        <h2>Office of Student Affairs Dashboard</h2>

        <div class="cards">
            <div class="card">
                <h3>Total Colleges</h3>
                <p>3</p>
            </div>

            <div class="card">
                <h3>Registered Orgs</h3>
                <p>32</p>
            </div>

            <div class="card">
                <h3>Total Students</h3>
                <p>2,143</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.cards {
    display: flex;
    gap: 20px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
</style>
