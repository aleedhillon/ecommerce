<script setup>
import { usePage } from '@inertiajs/vue3';
import AppLayout from './AppLayout.vue';
import { computed } from 'vue';

// const page = usePage();
// const errors = computed(() => page.props.errors);
</script>

<template>
    <AppLayout>
        <!-- <pre>
            {{ errors }}
        </pre> -->
        <slot></slot>
    </AppLayout>
</template>
