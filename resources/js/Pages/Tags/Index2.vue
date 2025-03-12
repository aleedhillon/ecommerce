<template>
    <div>
        <CrudComponent :config :items :filters :form :submitted>
            <template #columns>
                <Column field="name" header="Name"></Column>
                <Column field="is_active" header="Status">
                    <template #body="{ data }">
                        <Badge :severity="data.is_active ? 'success' : 'danger'">
                            {{ data.is_active ? 'Active' : 'Inactive' }}
                        </Badge>
                    </template>
                </Column>

                <Column field="created_at" header="Created At" sortable></Column>
                <Column field="updated_at" header="Updated At" sortable></Column>
            </template>

            <template #form>
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-2">Name</label>
                        <InputText id="name" v-model.trim="form.name" required="true" autofocus
                            :invalid="submitted && !form.name" fluid />
                        <small v-if="submitted && !form.name" class="text-red-500">Name is required.</small>
                    </div>
                </div>
                <div class="flex flex-col gap-6 mt-3">
                    <div>
                        <label for="is_active" class="block font-bold mb-2">Status</label>
                        <Select v-model="form.is_active" :options="statuses" optionLabel="label" optionValue="value"
                            placeholder="Select a status" class="w-full" :required="true" />
                        <small v-if="submitted && (form.is_active == null)" class="text-red-500">
                            Status is required.
                        </small>
                    </div>
                </div>
                <div class="flex flex-col gap-6 mt-3">
                    <div>
                        <label for="photo" class="block font-bold mb-2">Photo</label>
                        <FileUpload mode="basic" name="photo" customUpload @select="handlePhotoUpload" :auto="true"
                            accept="image/*" chooseLabel="Choose Image" class="w-full" />
                    </div>
                    <div>
                        <img v-if="form.photo || photoPreview" :src="photoPreview ?? resolveImagePath(form.photo)"
                            alt="Image" class="shadow-md rounded-xl w-full" style="filter: grayscale(100%)" />
                    </div>
                </div>
            </template>
        </CrudComponent>
    </div>
</template>
<script setup>
import CrudComponent from '@/Components/CrudComponent.vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { router, useForm, Link } from '@inertiajs/vue3';
import { Select } from 'primevue';
import { resolveImagePath } from '@/Helpers/imageHelper';
import { handlePagination } from '@/Helpers/pagination';
import debounce from 'lodash/debounce';
import { statuses } from '@/Helpers/enums.js';

const submitted = ref(false);

const form = useForm({
    name: '',
    is_active: null,
    photo: null,
})

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