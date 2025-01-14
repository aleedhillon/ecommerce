<template>
    <AuthenticatedLayout>
        <div class="card">
            <Toolbar class="mb-6">
                <template #start>
                    <Button label="New" icon="pi pi-plus" class="mr-2" @click="openNew" />
                    <Button label="Delete" icon="pi pi-trash" severity="danger" outlined @click="confirmDeleteSelected"
                        :disabled="!selectedBrands || !selectedBrands.length" />
                </template>

                <template #end>
                    <!-- <FileUpload mode="basic" :maxFileSize="1000000" label="Import" customUpload chooseLabel="Import"
                        class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" /> -->
                    <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
                </template>
            </Toolbar>

            <DataTable ref="dt" v-model:selection="selectedBrands" :value="brands.data" dataKey="id" :paginator="true"
                :rows="10" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} brands">
                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
                        <h4 class="m-0">Manage Brands</h4>
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Search..." />
                        </IconField>
                    </div>
                </template>

                <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
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
                        <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editBrand(slotProps.data)" />
                        <Button icon="pi pi-trash" outlined rounded severity="danger"
                            @click="confirmDeleteBrand(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Create & Edit Form Dialog -->
        <Dialog v-model:visible="brandDialog" maximizable :style="{ width: '600px' }" header="Brand Details"
            pt:mask:class="backdrop-blur-sm">
            <div class="flex flex-col gap-6">
                <div>
                    <label for="name" class="block font-bold mb-2">Name</label>
                    <InputText id="name" v-model.trim="form.name" required="true" autofocus
                        :invalid="submitted && !form.name" fluid />
                    <small v-if="submitted && !form.name" class="text-red-500">Name is required.</small>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div>
                    <label for="name" class="block font-bold mb-2">Description</label>
                    <Textarea v-model.trim="form.description" rows="4" cols="30" id="description" class="w-full" />
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
                    <!-- <small v-if="submitted && !form.photo" class="text-red-500">Photo is required.</small> -->
                </div>
                <div>
                    <img v-if="form.photo || photoPreview" :src="photoPreview ?? resolveImagePath(form.photo)"
                        alt="Image" class="shadow-md rounded-xl w-full" style="filter: grayscale(100%)" />
                </div>
            </div>

            <template #footer>
                <div class="mt-3">
                    <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
                    <Button label="Save & Continue" text icon="pi pi-check" @click="saveBrand(true)" v-if="!isEdit" />
                    <Button label="Save" icon="pi pi-check" @click="saveBrand(false)" v-if="!isEdit" />
                    <Button label="Update" icon="pi pi-check" @click="updateBrand" v-if="isEdit" />
                </div>
            </template>
        </Dialog>

        <!-- Single Delete -->
        <Dialog v-model:visible="deleteBrandDialog" :style="{ width: '450px' }" header="Confirm">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form.name">Are you sure you want to delete <b>{{ form.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteBrandDialog = false" />
                <Button label="Yes" icon="pi pi-check" @click="deleteBrand" />
            </template>
        </Dialog>

        <!-- Bulk Delete -->
        <Dialog v-model:visible="deleteBrandsDialog" :style="{ width: '450px' }" header="Confirm">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="form.name">Are you sure you want to delete the selected brands?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteBrandsDialog = false" />
                <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedBrands" />
            </template>
        </Dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, defineProps } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { router, useForm } from '@inertiajs/vue3';
import { Select } from 'primevue';
import { usePage } from '@inertiajs/vue3';
import { resolveImagePath } from '../../Helpers/imageHelper';

const { props } = usePage();

const vueProps = defineProps({
    brands: Object,
});

const toast = useToast();
const dt = ref();
const brandDialog = ref(false);
const deleteBrandDialog = ref(false);
const deleteBrandsDialog = ref(false);

const form = useForm({
    name: null,
    description: null,
    is_active: 1,
    photo: null,
});

const selectedBrands = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);

const statuses = [
    { label: 'Active', value: 1 },
    { label: 'Inactive', value: 0 },
];

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
    brandDialog.value = true;
    photoPreview.value = null;
};

const hideDialog = () => {
    brandDialog.value = false;
    submitted.value = false;
};

const saveBrand = (saveAndContinue = false) => {
    submitted.value = true;

    form.post(route('brands.store'), {
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
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something went wrong', life: 3000 });
        },
    });
};

const isEdit = ref(false);
const editingId = ref(null);

const updateBrand = () => {
    submitted.value = true;
    const url = route('brands.update', { brand: editingId.value });

    const data = {
        _method: 'put',
        name: form.name,
        description: form.description,
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

const editBrand = (prod) => {
    brandDialog.value = true;
    isEdit.value = true;

    photoPreview.value = null;
    form.name = prod.name;
    form.description = prod.description;
    form.is_active = prod.is_active;
    form.photo = prod.photo;
    editingId.value = prod.id;
};

const confirmDeleteBrand = (prod) => {
    deleteBrandDialog.value = true;

    photoPreview.value = null;
    form.name = prod.name;
    form.description = prod.description;
    form.is_active = prod.is_active;
    form.photo = prod.photo;
    editingId.value = prod.id;
};

const deleteBrand = () => {
    deleteBrandDialog.value = false;
    router.delete(route('brands.destroy', { brand: editingId.value }), {
        onSuccess: () => {
            toast.add({ severity: 'error', summary: 'Deleted', detail: 'Successfully Deleted', life: 3000 });
        }
    });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteBrandsDialog.value = true;
};

const deleteSelectedBrands = () => {
    const brandIds = selectedBrands.value.map(c => c.id);
    deleteBrandsDialog.value = false;
    selectedBrands.value = null;
    router.post(route('brands.bulk-destroy'), {
        brandIds: brandIds,
        onSuccess: () => {
            toast.add({ severity: 'error', summary: 'Deleted', detail: 'Selected Brands Deleted', life: 3000 });
        }
    })
};

</script>
