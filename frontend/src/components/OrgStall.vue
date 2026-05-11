<template>
    <div
        class="stall"
        :style="{ borderTop: '6px solid ' + getColor(org.category) }"
    >
        <div class="stall-name">{{ org.org_name }}</div>

        <span class="stall-cat" :style="{ color: getColor(org.category) }">
            {{ org.category }}
        </span>

        <div v-if="focused" class="stall-expanded">
            <div class="stall-banner">
                <img :src="org.banner_url || fallbackBanner" />
            </div>

            <div class="stall-desc">
                {{ org.description }}
            </div>
        </div>

        <!-- IMPORTANT FIX HERE -->
        <button class="stall-btn" @click="$emit('view', org)">Details</button>
    </div>
</template>

<script setup>
defineProps({
    org: Object,
    focused: Boolean,
    getColor: Function,
    fallbackBanner: String,
});

defineEmits(['view']);
</script>

<style scoped>
.stall {
    width: clamp(170px, 12vw, 200px);
    background: white;
    border-radius: 12px;
    padding: 14px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    transition: 0.3s;
}

.stall:hover {
    transform: translateY(-4px);
}

.stall-name {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 4px;
    min-height: 2.4em;
}

.stall-cat {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.stall-banner img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 8px;
}

.stall-desc {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 12px;
}

.stall-btn {
    padding: 8px;
    border: 2px solid #e2e8f0;
    background: white;
    color: #7f1d1d;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.stall-btn:hover {
    background: #7f1d1d;
    color: white;
}
</style>
