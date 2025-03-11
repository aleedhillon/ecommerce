<template>
    <div>
        <CrudComponent :config :items :filters></CrudComponent>
    </div>
</template>
<script setup>
import CrudComponent from '@/Components/CrudComponent.vue';

defineProps({
    items: Object,
    filters: {
        type: Object,
        default: () => ({
            search: '',
        }),
    },
});

const config = {
    title: 'Tags',
    modelSingular: 'tag',
    modelRaw: 'Tag',
    resource: 'tags',
    indexRoute: route('tags.index'),
    indexRouteTrashed: route('tags.index', { trashed: true }),
    storeRoute: route('tags.store'),
    updateRoute: route('tags.update', '__ID__'),
    deleteRoute: route('tags.destroy', '__ID__'),
    bulkDeleteRoute: route('tags.bulk-destroy'),
    bulkRestoreRoute: route('tags.bulk-restore'),
    bulkForceDeleteRoute: route('tags.bulk-force-delete'),
    exportRoute: route('tags.export'),
};
</script>