<template>
    <div
        class="lane"
        :class="{
            focused: focusedRow === rowIndex,
            dimmed: hoveredRow !== null && hoveredRow !== rowIndex,
        }"
        @mouseenter="hoveredRow = rowIndex"
        @mouseleave="hoveredRow = null"
    >
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

        <!-- CENTER -->
        <div class="center-control">
            <div class="path-line"></div>

            <button
                class="row-focus-btn"
                :class="{ active: focusedRow === rowIndex }"
                @click="$emit('toggle-row', rowIndex)"
                aria-label="Toggle row view"
            >
                <transition name="icon-fade" mode="out-in">
                    <span v-if="focusedRow === rowIndex" key="close">
                        Close
                    </span>

                    <span v-else key="open" class="eye-icon"> 👁 </span>
                </transition>
            </button>
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
import { ref } from 'vue';
import OrgStall from './OrgStall.vue';

const hoveredRow = ref(null);

const props = defineProps({
    row: { type: Array, default: () => [] },
    rowIndex: { type: Number, required: true },
    focusedRow: { type: Number, default: null },
    getColor: { type: Function, required: true },
    fallbackBanner: { type: String, default: '' },
});

const emit = defineEmits(['view-org', 'toggle-row']);

const handleView = (org) => {
    console.log('LANE GOT:', org);
    emit('view-org', org);
};
</script>

<style scoped>
/* MAIN LANE */
.lane {
    display: grid;
    grid-template-columns: auto auto auto;
    align-items: center;
    gap: clamp(20px, 2vw, 40px);
    width: max-content;
    transition: all 0.3s ease;
}

/* FOCUS EFFECT */
.lane:hover {
    transform: scale(1.03);
    filter: brightness(1.08);
}

.lane.dimmed {
    opacity: 0.35;
    filter: blur(1px) grayscale(0.3);
    transform: scale(0.98);
}

/* SIDES */
.lane-side {
    display: flex;
    gap: 16px;
}

/* CENTER */
.center-control {
    position: relative;
    width: clamp(90px, 8vw, 130px);
    height: 120px;

    display: flex;
    justify-content: center;
    align-items: center;
}

/* PATH LINE */
.path-line {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);

    width: 2px;

    background: repeating-linear-gradient(
        to bottom,
        #cbd5e1,
        #cbd5e1 6px,
        transparent 6px,
        transparent 12px
    );

    z-index: 1;
}

/* BUTTON (CENTERED ON LINE) */
.row-focus-btn {
    position: relative;
    z-index: 2;

    background: transparent;
    border: none;

    font-weight: 800;
    color: #7f1d1d;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    opacity: 0;
    transform: scale(0.7);
    transition: 0.25s ease;
    pointer-events: none;
}

/* SHOW ONLY ON ROW HOVER */
.lane:hover .row-focus-btn {
    opacity: 1;
    transform: scale(1);
    pointer-events: auto;
}

/* BIG EYE */
.eye-icon {
    font-size: 38px;
    line-height: 1;
}

/* hover */
.row-focus-btn:hover {
    transform: scale(1.15);
    color: #1e293b;
}

/* animation */
.icon-fade-enter-active,
.icon-fade-leave-active {
    transition: all 0.18s ease;
}

.icon-fade-enter-from,
.icon-fade-leave-to {
    opacity: 0;
    transform: scale(0.5);
}
</style>
