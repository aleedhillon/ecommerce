<template>
    <AuthenticatedLayout>
        <div class="card">
            <Toolbar class="">
                <template #start>
                    <Button label="New" icon="pi pi-plus" class="mr-2" @click="openNew" text />
                    <ButtonGroup class="mr-2">
                        <Link :href="vueProps.config.indexRoute">
                        <Button label="All Items" icon="pi pi-list" :class="{ 'border-bottom-2': !isTrashedPage }" text />
                        </Link>
                        <Link :href="vueProps.config.indexRouteTrashed">
                        <Button label="Trashed" icon="pi pi-ban" :class="{ 'border-bottom-2': isTrashedPage }" text />
                        </Link>
                    </ButtonGroup>
                    <Button label="Bulk Delete" icon="pi pi-trash" class="mr-2" severity="danger" outlined
                        @click="confirmDeleteSelected"
                        v-show="!(!selectedItems || !selectedItems?.length) && !isTrashedPage" />
                    <Button label="Bulk Restore" icon="pi pi-undo" class="mr-2" severity="warn" outlined
                        @click="restoreSelected"
                        v-show="!(!selectedItems || !selectedItems?.length) && isTrashedPage" />

                    <Button label="Force Delete" icon="pi pi-trash" class="mr-2" severity="danger" outlined
                        @click="forceDeleteSelected"
                        v-show="!(!selectedItems || !selectedItems?.length) && isTrashedPage" />
                </template>
                <template #end>
                    <Button label="Export" class="mx-2" icon="pi pi-upload" severity="secondary" @click="exportExcel" />
                </template>
            </Toolbar>

            <DataTable ref="dt" v-model:selection="selectedItems" :value="items.data" dataKey="id" :paginator="true"
                :rows="15" :filters="filters" :totalRecords="items.total" :lazy="true"
                @page="handlePagination($event, vueProps.config.indexRoute, vueProps.config.resource)"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                :currentPageReportTemplate="`Showing {first} to {last} of {totalRecords} ${vueProps.config.resource}`"
                resizableColumns columnResizeMode="fit">
                <template #empty>
                    <div class="p-4 text-center">
                        <p class="text-lg">No {{ vueProps.config.resource }} found.</p>
                    </div>
                </template>
                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
                        <h1 class="text-3xl">{{ vueProps.config.title }}</h1>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters.global.value" type="search" @input="debouncedSearch"
                                placeholder="Search..." clearable />
                        </IconField>
                    </div>
                </template>
                <Column selectionMode="multiple" style="width: 3rem" :exportable="false" header=""></Column>

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

                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editItem(slotProps.data)"
                            :disabled="isTrashedPage" />
                        <Button icon="pi pi-trash" outlined rounded severity="danger"
                            @click="confirmDeleteItem(slotProps.data)" :disabled="isTrashedPage" />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Create & Edit Form Dialog -->
        <Dialog v-model:visible="itemDialog" maximizable :style="{ width: '600px' }"
            :header="`${vueProps.config.modelRaw} Details`" pt:mask:class="backdrop-blur-sm">
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

            <template #footer>
                <div class="mt-3">
                    <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
                    <Button label="Save & Continue" text icon="pi pi-check" @click="saveItem(true)" v-if="!isEdit" />
                    <Button label="Save" icon="pi pi-check" @click="saveItem(false)" v-if="!isEdit" />
                    <Button label="Update" icon="pi pi-check" @click="updateItem" v-if="isEdit" />
                </div>
            </template>
        </Dialog>

        <!-- Single Delete -->
        <Dialog v-model:visible="deleteItemDialog" :style="{ width: '450px' }" header="Confirm">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span>Are you sure you want to delete <b>{{ form.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteItemDialog = false" />
                <Button label="Yes" icon="pi pi-check" @click="deleteItem" />
            </template>
        </Dialog>

        <!-- Bulk Delete -->
        <Dialog v-model:visible="deleteItemsDialog" :style="{ width: '450px' }" header="Confirm">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span>Are you sure you want to delete the selected items?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteItemsDialog = false" />
                <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedItems" />
            </template>
        </Dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, defineProps, onMounted, watch } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { router, useForm, Link } from '@inertiajs/vue3';
import { Select } from 'primevue';
import { resolveImagePath } from '@/Helpers/imageHelper';
import { handlePagination } from '@/Helpers/pagination';
import debounce from 'lodash/debounce';
import { statuses } from '@/Helpers/enums.js';

const vueProps = defineProps({
    config: Object,
    items: Object,
    filters: {
        type: Object,
        default: () => ({
            search: '',
        }),
    },
});

const toast = useToast();
const dt = ref();
const itemDialog = ref(false);
const deleteItemDialog = ref(false);
const deleteItemsDialog = ref(false);

const form = useForm({
    name: null,
    is_active: 1,
    photo: null,
});

const selectedItems = ref();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const submitted = ref(false);

const photoPreview = ref(null);
const handlePhotoUpload = (event) => {
    const file = event.files[0];
    form.photo = file;
    const reader = new FileReader();
    reader.onload = async (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const openNew = () => {
    isEdit.value = false;
    form.reset();
    submitted.value = false;
    itemDialog.value = true;
    photoPreview.value = null;
};

const hideDialog = () => {
    itemDialog.value = false;
    submitted.value = false;
};

const saveItem = (saveAndContinue = false) => {
    submitted.value = true;
    form.post(vueProps.config.storeRoute, {
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Successfully Created!', life: 3000 });
            if (saveAndContinue) {
                submitted.value = false;
                return;
            } else {
                hideDialog();
            }
        },
        onError: (errors) => {
            Object.entries(errors).forEach((val, key) => {
                toast.add({ severity: 'error', summary: 'Validation Error', detail: val[1], life: 3000 });
            })
        },
    });
};

const isEdit = ref(false);
const editingId = ref(null);

const updateItem = () => {
    submitted.value = true;
    const url = vueProps.config.updateRoute.replace('__ID__', editingId.value);

    const data = {
        _method: 'put',
        name: form.name,
        is_active: form.is_active,
        photo: form.photo,
    };

    router.post(url, data, {
        onSuccess: () => {
            hideDialog();
            photoPreview.value = null;
            form.reset();
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Successfully Updated!', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something went wrong, please try again!', life: 3000 });
        },
    });
};

const editItem = (prod) => {
    itemDialog.value = true;
    isEdit.value = true;

    photoPreview.value = null;
    form.name = prod.name;
    form.is_active = prod.is_active;
    form.photo = prod.photo;
    editingId.value = prod.id;
};

const confirmDeleteItem = (prod) => {
    deleteItemDialog.value = true;

    photoPreview.value = null;
    form.name = prod.name;
    form.is_active = prod.is_active;
    form.photo = prod.photo;
    editingId.value = prod.id;
};

const restoreSelected = () => {
    const itemIds = selectedItems.value.map(c => c.id);
    selectedItems.value = null;

    router.post(vueProps.config.bulkRestoreRoute, {
        ids: itemIds
    }, {
        onSuccess: () => {
            isTrashedPage.value = false;
            toast.add({ severity: 'success', summary: 'Restored', detail: 'Selected Items Restored!', life: 3000 });
        },
        onError: (errors) => {
            Object.entries(errors).forEach((val, key) => {
                toast.add({ severity: 'error', summary: 'Restore Error', detail: val[1], life: 3000 });
            })
        },
    })
};

const forceDeleteSelected = () => {
    const itemIds = selectedItems.value.map(c => c.id);
    selectedItems.value = null;

    router.post(vueProps.config.bulkForceDeleteRoute, {
        ids: itemIds
    }, {
        onSuccess: () => {
            isTrashedPage.value = false;
            toast.add({ severity: 'warn', summary: 'Permanently Delete', detail: 'Items Permanently Deleted!', life: 3000 });
        },
        onError: (errors) => {
            Object.entries(errors).forEach((val, key) => {
                toast.add({ severity: 'error', summary: 'Permanent Delete Error', detail: val[1], life: 3000 });
            })
        },
    })
};

const deleteItem = () => {
    deleteItemDialog.value = false;
    const url = vueProps.config.deleteRoute.replace('__ID__', editingId.value);
    router.delete(url, {
        onSuccess: () => {
            toast.add({ severity: 'error', summary: 'Deleted', detail: 'Successfully Deleted', life: 3000 });
        },
    });
};

const exportExcel = () => {
    const params = new URLSearchParams({
        search: filters.value.global.value || ''
    }).toString();

    // Create a temporary link to trigger the download
    const link = document.createElement('a');
    link.href = `${vueProps.config.exportRoute}?${params}`;
    link.setAttribute('download', ''); // This is optional as the server will send the filename
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    toast.add({
        severity: 'success',
        summary: 'Export Started',
        detail: 'Your export will download shortly.',
        life: 3000
    });
};

const confirmDeleteSelected = () => {
    deleteItemsDialog.value = true;
};

const deleteSelectedItems = () => {
    const itemIds = selectedItems.value.map(c => c.id);
    deleteItemsDialog.value = false;
    selectedItems.value = null;

    router.post(vueProps.config.bulkDeleteRoute, {
        ids: itemIds
    }, {
        onSuccess: () => {
            toast.add({ severity: 'error', summary: 'Deleted', detail: 'Selected Items Deleted', life: 3000 });
        },
        onError: (errors) => {
            Object.entries(errors).forEach((val, key) => {
                toast.add({ severity: 'error', summary: 'Delete Error', detail: val[1], life: 3000 });
            })
        },
    })
};

const debouncedSearch = debounce((e) => {
    filters.value.global.value = e.target.value;
    router.get(
        vueProps.config.indexRoute,
        {
            search: e.target.value,
            per_page: dt.value?.rows
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['items']
        }
    );
}, 300);

onMounted(() => {
    if (vueProps.filters.search) {
        filters.value.global.value = vueProps.filters.search;
    }
});

const isTrashedPage = ref(route().params.trashed === '1');

</script>
