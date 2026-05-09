<template>
    <div class="lane" :class="{ focused: focusedRow === rowIndex }">
        <!-- LEFT SIDE -->
        <div class="lane-side left">
            <OrgStall
                v-for="org in row.slice(0, 2)"
                :key="org.org_id"
                :org="org"
                :focused="focusedRow === rowIndex"
                :getColor="getColor"
                :fallbackBanner="fallbackBanner"
                @view="handleView"
            />
        </div>

        <!-- CENTER PATH -->
        <div class="path">
            <div class="row-tag">
                ROW {{ String.fromCharCode(65 + rowIndex) }}
            </div>

            <button
                class="row-focus-btn"
                :class="{ active: focusedRow === rowIndex }"
                @click="$emit('toggle-row', rowIndex)"
            >
                {{ focusedRow === rowIndex ? 'Close' : 'View Orgs' }}
            </button>

            <div class="path-line"></div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="lane-side right">
            <OrgStall
                v-for="org in row.slice(2, 5)"
                :key="org.org_id"
                :org="org"
                :focused="focusedRow === rowIndex"
                :getColor="getColor"
                :fallbackBanner="fallbackBanner"
                @view="handleView"
            />
        </div>
    </div>
</template>

<script setup>
import OrgStall from './OrgStall.vue';

const props = defineProps({
    row: {
        type: Array,
        default: () => [],
    },
    rowIndex: {
        type: Number,
        required: true,
    },
    focusedRow: {
        type: Number,
        default: null,
    },
    getColor: {
        type: Function,
        required: true,
    },
    fallbackBanner: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['view-org', 'toggle-row']);

const handleView = (org) => {
    console.log('LANE GOT:', org);
    emit('view-org', org);
};
</script>

<style scoped>
.lane {
    display: grid;
    grid-template-columns: auto auto auto;
    align-items: center;
    gap: clamp(20px, 2vw, 40px);
    width: max-content;
    transition: transform 0.2s ease;
}

.lane.focused {
    transform: scale(1.02);
}

/* SIDES */
.lane-side {
    display: flex;
    gap: 16px;
}

.lane-side.left,
.lane-side.right {
    justify-content: flex-start;
}

/* PATH */
.path {
    width: clamp(90px, 8vw, 130px);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.row-tag {
    font-size: 10px;
    font-weight: 800;
    color: #7f1d1d;
    margin-bottom: 4px;
}

.row-focus-btn {
    background: white;
    border: 1px solid #7f1d1d;
    color: #7f1d1d;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 20px;
    cursor: pointer;
    margin-bottom: 8px;
    transition: 0.2s;
}

.row-focus-btn:hover {
    background: #7f1d1d;
    color: white;
}

.row-focus-btn.active {
    background: #1e293b;
    color: white;
    border-color: #1e293b;
}

.path-line {
    width: 2px;
    height: 100px;
    background: repeating-linear-gradient(
        to bottom,
        #cbd5e1,
        #cbd5e1 6px,
        transparent 6px,
        transparent 12px
    );
}
</style>
