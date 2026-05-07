<template>
    <div class="page">
        <h2>Colleges and Degree Programs</h2>

        <table>
            <thead>
                <tr>
                    <th>College</th>
                    <th>Degree Program</th>
                    <th>Organizations</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>College of Science & Math</td>
                    <td>BS Computer Science</td>
                    <td>AWS Cloud Club</td>
                </tr>

                <tr>
                    <td>School of Management</td>
                    <td>BS ABE</td>
                    <td>JMA</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
.page {
    padding-left: 20px;
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
