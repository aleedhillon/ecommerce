<template>
    <div>
        <CrudComponent :form formWidth="90vw">
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

            <template #form="{ submitted, handlePhotoUpload, photoPreview, resolveImagePath }">
                <div class="formgrid grid">
                    <!-- Basic Information Section -->
                    <div class="col-12">
                        <h3 class="text-xl font-semibold mb-3">Basic Information</h3>
                    </div>

                    <!-- Product Name -->
                    <div class="field col-12 md:col-6">
                        <label for="name" class="block font-bold mb-2">Name *</label>
                        <InputText id="name" v-model.trim="form.name" required class="w-full"
                            :class="{ 'p-invalid': submitted && !form.name }" />
                        <small v-if="submitted && !form.name" class="p-error">Name is required.</small>
                    </div>

                    <!-- Product Slug -->
                    <div class="field col-12 md:col-6">
                        <label for="slug" class="block font-bold mb-2">Slug *</label>
                        <InputText id="slug" v-model.trim="form.slug" required class="w-full"
                            :class="{ 'p-invalid': submitted && !form.slug }" />
                        <small v-if="submitted && !form.slug" class="p-error">Slug is required.</small>
                    </div>

                    <!-- SKU, Barcode, Code -->
                    <div class="field col-12 md:col-4">
                        <label for="sku" class="block font-bold mb-2">SKU</label>
                        <InputText id="sku" v-model.trim="form.sku" class="w-full" />
                    </div>

                    <div class="field col-12 md:col-4">
                        <label for="barcode" class="block font-bold mb-2">Barcode</label>
                        <InputText id="barcode" v-model.trim="form.barcode" class="w-full" />
                    </div>

                    <div class="field col-12 md:col-4">
                        <label for="code" class="block font-bold mb-2">Product Code</label>
                        <InputText id="code" v-model.trim="form.code" class="w-full" />
                    </div>

                    <!-- Product Type -->
                    <div class="field col-12 md:col-4">
                        <label for="type" class="block font-bold mb-2">Product Type *</label>
                        <Select id="type" v-model="form.type" :options="productTypes" optionLabel="label"
                            optionValue="value" placeholder="Select Type" class="w-full"
                            :class="{ 'p-invalid': submitted && !form.type }" />
                        <small v-if="submitted && !form.type" class="p-error">Product type is required.</small>
                    </div>

                    <!-- Status -->
                    <div class="field col-12 md:col-4">
                        <label for="status" class="block font-bold mb-2">Status</label>
                        <ToggleSwitch v-model="form.is_active" />
                        <span class="ml-2">{{ form.is_active ? 'Active' : 'Inactive' }}</span>
                    </div>

                    <!-- Stock Status -->
                    <div class="field col-12 md:col-4">
                        <label for="stock_status" class="block font-bold mb-2">Stock Status</label>
                        <Select id="stock_status" v-model="form.stock_status" :options="stockStatuses"
                            optionLabel="label" optionValue="value" placeholder="Select Status" class="w-full" />
                    </div>

                    <!-- Pricing Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Pricing</h3>
                    </div>

                    <!-- Base Price -->
                    <div class="field col-12 md:col-6">
                        <label for="base_price" class="block font-bold mb-2">Base Price *</label>
                        <InputNumber id="base_price" v-model="form.base_price" mode="currency" currency="USD"
                            locale="en-US" class="w-full" :class="{ 'p-invalid': submitted && !form.base_price }" />
                        <small v-if="submitted && !form.base_price" class="p-error">Base price is required.</small>
                    </div>

                    <!-- Discount Price -->
                    <div class="field col-12 md:col-6">
                        <label for="base_discount_price" class="block font-bold mb-2">Discount Price</label>
                        <InputNumber id="base_discount_price" v-model="form.base_discount_price" mode="currency"
                            currency="USD" locale="en-US" class="w-full" :max="form.base_price" />
                        <small v-if="form.base_discount_price && form.base_discount_price >= form.base_price"
                            class="p-error">
                            Discount price must be less than base price.
                        </small>
                    </div>

                    <!-- Inventory Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Inventory</h3>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="field col-12 md:col-6">
                        <label for="stock_quantity" class="block font-bold mb-2">Stock Quantity</label>
                        <InputNumber id="stock_quantity" v-model="form.stock_quantity" class="w-full" :min="0" />
                    </div>

                    <!-- Categories Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Categories & Attributes</h3>
                    </div>

                    <!-- Category -->
                    <div class="field col-12 md:col-6">
                        <label for="category_id" class="block font-bold mb-2">Category</label>
                        <Select id="category_id" v-model="form.category_id" :options="categories" optionLabel="name"
                            optionValue="id" placeholder="Select Category" class="w-full" @change="loadSubCategories" />
                    </div>

                    <!-- Sub Category -->
                    <div class="field col-12 md:col-6">
                        <label for="sub_category_id" class="block font-bold mb-2">Sub-Category</label>
                        <Select id="sub_category_id" v-model="form.sub_category_id" :options="subCategories"
                            optionLabel="name" optionValue="id" placeholder="Select Sub-Category" class="w-full"
                            :disabled="!form.category_id" />
                    </div>

                    <!-- Brand -->
                    <div class="field col-12 md:col-6">
                        <label for="brand_id" class="block font-bold mb-2">Brand</label>
                        <Select id="brand_id" v-model="form.brand_id" :options="brands" optionLabel="name"
                            optionValue="id" placeholder="Select Brand" class="w-full" />
                    </div>

                    <!-- Tax -->
                    <div class="field col-12 md:col-6">
                        <label for="tax_id" class="block font-bold mb-2">Tax</label>
                        <Select id="tax_id" v-model="form.tax_id" :options="taxes" optionLabel="name" optionValue="id"
                            placeholder="Select Tax" class="w-full" />
                    </div>

                    <!-- Physical Properties Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Physical Properties</h3>
                    </div>

                    <!-- Weight -->
                    <div class="field col-12 md:col-4">
                        <label for="weight" class="block font-bold mb-2">Weight</label>
                        <InputText id="weight" v-model.trim="form.weight" class="w-full" placeholder="e.g. 1.5kg" />
                    </div>

                    <!-- Dimensions -->
                    <div class="field col-12 md:col-4">
                        <label for="dimensions" class="block font-bold mb-2">Dimensions</label>
                        <InputText id="dimensions" v-model.trim="form.dimensions" class="w-full"
                            placeholder="e.g. 10x20x30 cm" />
                    </div>

                    <!-- Materials -->
                    <div class="field col-12 md:col-4">
                        <label for="materials" class="block font-bold mb-2">Materials</label>
                        <InputText id="materials" v-model.trim="form.materials" class="w-full"
                            placeholder="e.g. Cotton, Polyester" />
                    </div>

                    <!-- Description Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Description & Details</h3>
                    </div>

                    <!-- Description -->
                    <div class="field col-12">
                        <label for="description" class="block font-bold mb-2">Description</label>
                        <Editor v-model="form.description" editorStyle="height: 250px" />
                    </div>

                    <!-- Additional Info -->
                    <div class="field col-12">
                        <label for="additional_info" class="block font-bold mb-2">Additional Information</label>
                        <Editor v-model="form.additional_info" editorStyle="height: 150px" />
                    </div>

                    <!-- SEO Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">SEO Information</h3>
                    </div>

                    <!-- Meta Title -->
                    <div class="field col-12 md:col-6">
                        <label for="meta_title" class="block font-bold mb-2">Meta Title</label>
                        <InputText id="meta_title" v-model.trim="form.meta_title" class="w-full" />
                    </div>

                    <!-- Meta Keywords -->
                    <div class="field col-12 md:col-6">
                        <label for="meta_keywords" class="block font-bold mb-2">Meta Keywords</label>
                        <InputText id="meta_keywords" v-model.trim="form.meta_keywords" class="w-full" />
                    </div>

                    <!-- Meta Description -->
                    <div class="field col-12">
                        <label for="meta_description" class="block font-bold mb-2">Meta Description</label>
                        <Textarea id="meta_description" v-model="form.meta_description" rows="4" class="w-full" />
                    </div>

                    <!-- Image Upload Section -->
                    <div class="col-12 mt-4">
                        <h3 class="text-xl font-semibold mb-3">Product Image</h3>
                    </div>

                    <!-- Thumbnail -->
                    <div class="field col-12">
                        <label for="thumbnail" class="block font-bold mb-2">Thumbnail</label>
                        <div class="flex align-items-center gap-4">
                            <div v-if="photoPreview || form.thumbnail" class="thumbnail-preview mb-3">
                                <img :src="photoPreview ? photoPreview : resolveImagePath(form.thumbnail)"
                                    alt="Product Thumbnail" class="w-20 h-20 object-cover rounded" />
                            </div>
                            <FileUpload mode="basic" name="thumbnail" accept="image/*" :maxFileSize="2000000"
                                @select="handlePhotoUpload" :auto="true" chooseLabel="Browse" />
                        </div>
                        <small class="text-gray-500">Max size: 2MB. Accepted formats: JPEG, PNG, JPG, GIF</small>
                    </div>
                </div>
            </template>
        </CrudComponent>
    </div>
</template>
<script setup>
import CrudComponent from '@/Components/CrudComponent.vue';
import { useForm } from '@inertiajs/vue3';
import { Select, ToggleSwitch } from 'primevue';
import { ref, watch, onMounted } from 'vue';

// Define form
const form = useForm({
    name: '',
    slug: '',
    sku: '',
    barcode: '',
    code: '',
    base_price: 0,
    base_discount_price: null,
    stock_quantity: 0,
    stock_status: 'in_stock',
    type: 'simple',
    weight: '',
    dimensions: '',
    materials: '',
    description: '',
    additional_info: '',
    is_active: true,
    thumbnail: null,
    meta_title: '',
    meta_description: '',
    meta_keywords: '',
    category_id: null,
    sub_category_id: null,
    tax_id: null,
    brand_id: null,
});

// Define options for dropdowns
const productTypes = [
    { label: 'Simple Product', value: 'simple' },
    { label: 'Variable Product', value: 'variable' }
];

const stockStatuses = [
    { label: 'In Stock', value: 'in_stock' },
    { label: 'Out of Stock', value: 'out_of_stock' },
    { label: 'Pre-Order', value: 'pre_order' }
];

// These would normally come from your backend via props
// const categories = ref([]);
// const subCategories = ref([]);
// const brands = ref([]);
// const taxes = ref([]);

const { categories, subCategories, brands, taxes } = defineProps(['categories', 'subCategories', 'brands', 'taxes']);

// Function to load sub-categories when category changes
const loadSubCategories = () => {
    if (form.category_id) {
        // In a real app, you'd fetch subcategories from the server
        // For now, just reset the subcategory selection
        form.sub_category_id = null;
        // Fetch subcategories based on category_id
        // Example: axios.get(`/api/categories/${form.category_id}/subcategories`)
        //   .then(response => {
        //     subCategories.value = response.data;
        //   });
    } else {
        subCategories.value = [];
        form.sub_category_id = null;
    }
};

// Auto-generate slug from name
watch(() => form.name, (newValue) => {
    if (!form.slug || form.slug === '') {
        form.slug = newValue
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
});

// Load initial data (would be from props in real app)
onMounted(() => {
    // Example of fetching data:
    // axios.get('/api/product-form-data').then(response => {
    //   categories.value = response.data.categories;
    //   brands.value = response.data.brands;
    //   taxes.value = response.data.taxes;
    // });
});
</script>
<style scoped>
.p-editor-container .p-editor-content .ql-editor {
    min-height: 150px;
}
</style>