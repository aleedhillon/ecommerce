<template>
    <div class="min-h-[90vh]">
        <div class="min-h-[400px] border border-5 border-red-500 flex flex-col items-center justify-center gap-5">
            <h1 class="text-4xl">Welcome page</h1>
            <Button severity="primary">
                <Link href="/login">Login</Link>
            </Button>
        </div>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3'
import { Button } from 'primevue';

</script>
