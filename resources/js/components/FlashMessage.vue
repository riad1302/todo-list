<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();

const successMessage = ref('');
const errorMessage = ref('');

// Watch for flash messages from Inertia props
watch(
    () => props.flash,
    (flash) => {
        console.log(flash?.success)
        successMessage.value = flash?.success || '';
        errorMessage.value = flash?.error || '';
    },
    { immediate: true }
);

const close = () => {
    successMessage.value = '';
    errorMessage.value = '';
};
</script>

<template>
    <div class="fixed top-5 right-5 z-50 space-y-2 max-w-xs">
        <div
            v-if="successMessage"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
            role="alert"
        >
            <strong class="font-bold">Success! </strong>
            <span class="block sm:inline">{{ successMessage }}</span>
            <button
                @click="close"
                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                aria-label="Close"
            >
                &times;
            </button>
        </div>

        <div
            v-if="errorMessage"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
            role="alert"
        >
            <strong class="font-bold">Error! </strong>
            <span class="block sm:inline">{{ errorMessage }}</span>
            <button
                @click="close"
                class="absolute top-0 bottom-0 right-0 px-4 py-3"
                aria-label="Close"
            >
                &times;
            </button>
        </div>
    </div>
</template>

<style scoped>
button {
    background: transparent;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    line-height: 1;
}
</style>
