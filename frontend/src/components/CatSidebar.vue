<template>
    <aside class="sidebar" :class="{ collapsed: !categoriesOpen }">
        <button class="sidebar-toggle" @click="$emit('toggle')">
            <span v-if="categoriesOpen">CATEGORIES</span>
            <span class="arrow" :class="{ open: !categoriesOpen }">❮</span>
        </button>

        <div v-show="categoriesOpen" class="cat-list">
            <button
                class="cat-btn all"
                :class="{ active: selectedCategory === '' }"
                @click="$emit('update-category', '')"
                style="--cat-color: #7f1d1d"
            >
                All Categories
            </button>

            <button
                v-for="c in categories"
                :key="c"
                class="cat-btn"
                :class="{ active: selectedCategory === c }"
                @click="$emit('update-category', c)"
                :style="{ '--cat-color': getColor(c) }"
            >
                {{ c }}
            </button>
        </div>
    </aside>
</template>

<script setup>
defineProps({
    categories: Array,
    selectedCategory: String,
    categoriesOpen: Boolean,
    getColor: Function,
});

defineEmits(['update-category', 'toggle']);
</script>

<style scoped>
.sidebar {
    width: 220px;
    background: white;
    padding: 20px;
    border-radius: 16px;
    position: sticky;
    top: 24px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: width 0.3s ease;
    overflow: hidden;
}

.sidebar.collapsed {
    width: 60px;
    padding: 20px 12px;
}

/* toggle */
.sidebar-toggle {
    width: 100%;
    border: none;
    background: none;
    padding: 6px 4px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 11px;
    font-weight: 800;
    color: #64748b;
    cursor: pointer;
}

.arrow {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 900;
    border-radius: 10px;
    background: #f1f5f9;
    color: #7f1d1d;
    transition: 0.2s;
}

.arrow.open {
    transform: rotate(180deg);
}

.sidebar-toggle:hover .arrow {
    background: #7f1d1d;
    color: white;
}

/* categories */
.cat-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cat-btn {
    width: 100%;
    text-align: left;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
    cursor: pointer;
    font-size: 13px;
    background-color: white;
    color: #333;

    border-left: 5px solid var(--cat-color);
    transition: all 0.2s ease;
}

.cat-btn:hover {
    background-color: var(--cat-color);
    color: white;
    transform: translateX(2px);
}

.cat-btn.active {
    background-color: var(--cat-color);
    color: white;
    font-weight: 600;
}

.cat-btn.active:hover {
    filter: brightness(0.95);
}
</style>
