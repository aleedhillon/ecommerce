<template>
    <AuthenticatedLayout>

        <div class="card">
            <Toolbar class="mb-6">
                <template #start>
                    <Button label="New" icon="pi pi-plus" class="mr-2" @click="openNew" />
                    <Button label="Delete" icon="pi pi-trash" severity="danger" outlined @click="confirmDeleteSelected"
                        :disabled="!selectedCategories || !selectedCategories.length" />
                </template>

                <template #end>
                    <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" customUpload
                        chooseLabel="Import" class="mr-2" auto :chooseButtonProps="{ severity: 'secondary' }" />
                    <Button label="Export" icon="pi pi-upload" severity="secondary" @click="exportCSV($event)" />
                </template>
            </Toolbar>

            <DataTable ref="dt" v-model:selection="selectedCategories" :value="categories" dataKey="id"
                :paginator="true" :rows="10" :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} categories">
                <template #header>
                    <div class="flex flex-wrap gap-2 items-center justify-between">
                        <h4 class="m-0">Manage Categories</h4>
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
                        <Button icon="pi pi-pencil" outlined rounded class="mr-2"
                            @click="editCategory(slotProps.data)" />
                        <Button icon="pi pi-trash" outlined rounded severity="danger"
                            @click="confirmDeleteCategory(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </div>
        <!-- Create Dialog -->
        <Dialog v-model:visible="categoryDialog" :style="{ width: '450px' }" header="Category Details" :modal="true">
            {{ category }}
            <div class="flex flex-col gap-6">
                <div>
                    <label for="name" class="block font-bold mb-2">Name</label>
                    <InputText id="name" v-model.trim="category.name" required="true" autofocus
                        :invalid="submitted && !category.name" fluid />
                    <small v-if="submitted && !category.name" class="text-red-500">Name is required.</small>
                </div>
            </div>
            <div class="flex flex-col gap-6 mt-3">
                <div>
                    <label for="is_active" class="block font-bold mb-2">Status</label>
                    <Dropdown v-model="category.is_active" :options="statuses" optionLabel="label" optionValue="value"
                        placeholder="Select a status" class="w-full" />
                    <small v-if="submitted && (category.is_active == null)" class="text-red-500">Status is
                        required.</small>
                </div>
            </div>
            <div class="flex flex-col gap-6 mt-3">

                <div>
                    <label for="thumbnail" class="block font-bold mb-2">Thumbnail</label>
                    <FileUpload mode="basic" name="thumbnail" customUpload @select="handleThumbnailUpload" :auto="true"
                        accept="image/*" chooseLabel="Choose Image" class="w-full" />
                    <!-- <small v-if="submitted && !category.thumbnail" class="text-red-500">Thumbnail is required.</small> -->
                </div>
                <div>
                    <img v-if="category.thumbnail || thumbnailPreview" :src="thumbnailPreview ?? category.thumbnail"
                        alt="Image" class="shadow-md rounded-xl w-full" style="filter: grayscale(100%)" />
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Save" icon="pi pi-check" @click="saveCategory" v-if="!isEdit" />
                <Button label="Update" icon="pi pi-check" @click="updateCategory" v-if="isEdit" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteCategoryDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="category">Are you sure you want to delete <b>{{ category.name }}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteCategoryDialog = false" />
                <Button label="Yes" icon="pi pi-check" @click="deleteCategory" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteCategoriesDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="category">Are you sure you want to delete the selected categories?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" text @click="deleteCategoriesDialog = false" />
                <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedCategories" />
            </template>
        </Dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, defineProps } from 'vue';
import { FilterMatchMode } from '@primevue/core/api';
import { useToast } from 'primevue/usetoast';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
});

const toast = useToast();
const dt = ref();
const categoryDialog = ref(false);
const deleteCategoryDialog = ref(false);
const deleteCategoriesDialog = ref(false);

const init = {
    name: null,
    is_active: null,
    thumbnail: null,
};

const category = ref({ ...init });

const selectedCategories = ref();
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);

const statuses = [
    { label: 'Active', value: true },
    { label: 'Inactive', value: false },
];

const thumbnailPreview = ref(null);

const handleThumbnailUpload = (event) => {
    const file = event.files[0];
    category.value.thumbnail = file;
    const reader = new FileReader();

    reader.onload = async (e) => {
        thumbnailPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

const openNew = () => {
    category.value = { ...init };
    submitted.value = false;
    categoryDialog.value = true;
};

const hideDialog = () => {
    categoryDialog.value = false;
    submitted.value = false;
};

const saveCategory = () => {
    submitted.value = true;
    const categoryForm = useForm({ ...category.value });
    categoryForm.post(route('categories.store'), {
        onSuccess: () => {
            hideDialog();
            category.value = {};
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Category Created', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something went wrong', life: 3000 });
        },
    });
};

const isEdit = ref(false);
const editingId = ref(null);

const updateCategory = () => {
    submitted.value = true;
    const url = route('categories.update', { category: editingId.value });

    const categoryUpdateForm = useForm({ ...category.value });
    categoryUpdateForm.put(url), {
        onSuccess: (res) => {
            console.log(res);
            hideDialog();
            category.value = { ...init };
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Category Updated', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Something went wrong', life: 3000 });
        },
    };
};

const editCategory = (prod) => {
    categoryDialog.value = true;
    isEdit.value = true;

    category.value = {
        name: prod.name,
        is_active: prod.is_active ? true : false,
        thumbnail: prod.thumbnail,
    };
    editingId.value = prod.id;
};

const confirmDeleteCategory = (prod) => {
    category.value = prod;
    deleteCategoryDialog.value = true;
};

const deleteCategory = () => {
    categories.value = categories.value.filter(val => val.id !== category.value.id);
    deleteCategoryDialog.value = false;
    category.value = {};
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Category Deleted', life: 3000 });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteCategoriesDialog.value = true;
};

const deleteSelectedCategories = () => {
    categories.value = categories.value.filter(val => !selectedCategories.value.includes(val));
    deleteCategoriesDialog.value = false;
    selectedCategories.value = null;
    toast.add({ severity: 'success', summary: 'Successful', detail: 'Categories Deleted', life: 3000 });
};

</script>
