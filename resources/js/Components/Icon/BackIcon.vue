<template>
    <svg :xmlns="xmlns" :width="width" :height="height" :fill="fill" :viewBox="viewBox" class="bi bi-chevron-compact-left">
        <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 0 1 .223.67L6.56 8l2.888 5.776a.5.5 0 1 1-.894.448l-3-6a.5.5 0 0 1 0-.448l3-6a.5.5 0 0 1 .67-.223"/>
    </svg>
</template>

<script setup>
const props = defineProps({
    width: {
        type: String,
        default: '24',
    },
    height: {
        type: String,
        default: '24',
    },
    fill: {
        type: String,
        default: 'currentColor',
    },
    xmlns: {
        type: String,
        default: 'http://www.w3.org/2000/svg',
    },
    viewBox: {
        type: String,
        default: '0 0 16 16',
    },
});
</script>