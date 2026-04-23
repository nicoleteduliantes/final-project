<template>
<div class="page">
    <h2>Organization Dashboard</h2>

    <div class="cards">
        <div class="card">
        <h3>Total Members</h3>
        <p>120</p>
        </div>

        <div class="card">
        <h3>Pending Applications</h3>
        <p>8</p>
        </div>

        <div class="card">
        <h3>Upcoming Events</h3>
        <p>3</p>
        </div>
    </div>

    <div class="section">
        <h3>Announcements</h3>
        <textarea placeholder="Post announcement..."></textarea>
        <button>Post</button>
    </div>
</div>
</template>

<style scoped>
.page { padding:20px }
.cards { display:flex; gap:20px; margin-bottom:20px }
.card { background:#fff; padding:20px; border:1px solid #eee }
textarea { width:100%; height:100px }
</style>