<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Modal from '@/Components/Modal.vue';
import InputText from 'primevue/inputtext';
import Checkbox from 'primevue/checkbox';
import Dropdown from 'primevue/dropdown';

const categories = ref([]);
const subcategories = ref([]);

const loadingCategories = ref(false);
const loadingSubcategories = ref(false);

const categoryDialogVisible = ref(false);
const subcategoryDialogVisible = ref(false);

const editingCategory = ref(null);
const editingSubcategory = ref(null);

const categoryForm = ref({
    id: '',
    name: '',
    translated: '',
});

const subcategoryForm = ref({
    id: '',
    name: '',
    translated: '',
    product_category_id: '',
});

const fireMessage = (message, success = false, reload = false) => {
  Swal.fire({
    position: "top",
    toast: true,
    icon: success ? "success" : "error",
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (reload) {
      location.reload();
    }
  });
};

onMounted(async () => {
    checkPermission();
    await loadCategories();
    await loadSubcategories();
});

const checkPermission = ()=>{
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1' || logged_user.value.team_id != '2') {
        location.href = '/dashboard';
    }
}


async function loadCategories(){
    try{
        loadingCategories.value = true;
        const response = await fetch('/product-categories/fetch');
        if(!response.ok){
            throw new Error('Failed to fetch categories');
        }
        const data = await response.json();
        if(data.success){
            categories.value = data.data;
        }
    }catch(error){
        console.log(error);
    }finally{
        loadingCategories.value = false;
    }
}

async function loadSubcategories(){
    try{
        loadingSubcategories.value = true;
        const response = await fetch('/product-subcategories/fetch');
        if(!response.ok){
            throw new Error('Failed to fetch subcategories');
        }
        const data = await response.json();
        if(data.success){
            subcategories.value = data.data;
        }
    }catch(error){
        console.log(error);
    }finally{
        loadingSubcategories.value = false;
    }
}

function openCategoryDialog(category = null){
    if(category){
        editingCategory.value = category;
        categoryForm.value = {
            id: category.id,
            name: category.name,
            translated: category.translated,
        };
    }else{
        editingCategory.value = null;
        categoryForm.value = {
            id: '',
            name: '',
            translated: '',
        };
    }
    categoryDialogVisible.value = true;
}

function closeCategoryDialog(){
    categoryDialogVisible.value = false;
}

async function saveCategory(){
    if(!categoryForm.value.name || !categoryForm.value.translated){
        fireMessage('Please fill all required fields');
        return;
    }
    if(categoryForm.value.id){
        await updateCategory();
    }else{
        await addCategory();
    }
}

async function addCategory(){
    try{
        const formData = new FormData();
        formData.append('name', categoryForm.value.name);
        formData.append('translated', categoryForm.value.translated);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-categories/store', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Category created successfully', true, false);
            await loadCategories();
            closeCategoryDialog();
        }else{
            fireMessage('Error creating category');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error creating category');
    }
}

async function updateCategory(){
    try{
        const formData = new FormData();
        formData.append('id', categoryForm.value.id);
        formData.append('name', categoryForm.value.name);
        formData.append('translated', categoryForm.value.translated);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-categories/update', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Category updated successfully', true, false);
            await loadCategories();
            closeCategoryDialog();
        }else{
            fireMessage('Error updating category');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error updating category');
    }
}

function confirmDeleteCategory(category){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(async(result) => {
        if (result.isConfirmed) {
            await deleteCategory(category.id);
        }
    });
}

async function deleteCategory(id){
    try{
        const formData = new FormData();
        formData.append('id', id);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-categories/delete', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Category deleted successfully', true, false);
            await loadCategories();
        }else{
            fireMessage('Error deleting category');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error deleting category');
    }
}

function openSubcategoryDialog(subcategory = null){
    if(subcategory){
        editingSubcategory.value = subcategory;
        subcategoryForm.value = {
            id: subcategory.id,
            name: subcategory.name,
            translated: subcategory.translated,
            product_category_id: subcategory.product_category_id,
        };
    }else{
        editingSubcategory.value = null;
        subcategoryForm.value = {
            id: '',
            name: '',
            translated: '',
            product_category_id: '',
        };
    }
    subcategoryDialogVisible.value = true;
}

function closeSubcategoryDialog(){
    subcategoryDialogVisible.value = false;
}

async function saveSubcategory(){
    if(!subcategoryForm.value.name || !subcategoryForm.value.translated || !subcategoryForm.value.product_category_id){
        fireMessage('Please fill all required fields');
        return;
    }
    if(subcategoryForm.value.id){
        await updateSubcategory();
    }else{
        await addSubcategory();
    }
}

async function addSubcategory(){
    try{
        const formData = new FormData();
        formData.append('name', subcategoryForm.value.name);
        formData.append('translated', subcategoryForm.value.translated);
        formData.append('product_category_id', subcategoryForm.value.product_category_id);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-subcategories/store', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Subcategory created successfully', true, false);
            await loadSubcategories();
            closeSubcategoryDialog();
        }else{
            fireMessage('Error creating subcategory');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error creating subcategory');
    }
}

async function updateSubcategory(){
    try{
        const formData = new FormData();
        formData.append('id', subcategoryForm.value.id);
        formData.append('name', subcategoryForm.value.name);
        formData.append('translated', subcategoryForm.value.translated);
        formData.append('product_category_id', subcategoryForm.value.product_category_id);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-subcategories/update', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Subcategory updated successfully', true, false);
            await loadSubcategories();
            closeSubcategoryDialog();
        }else{
            fireMessage('Error updating subcategory');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error updating subcategory');
    }
}

function confirmDeleteSubcategory(subcategory){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(async(result) => {
        if (result.isConfirmed) {
            await deleteSubcategory(subcategory.id);
        }
    });
}

async function deleteSubcategory(id){
    try{
        const formData = new FormData();
        formData.append('id', id);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const response = await fetch('/admin/product-subcategories/delete', {
            method: 'POST',
            body: formData,
        });
        const data = await response.json();
        if(data.success){
            fireMessage('Subcategory deleted successfully', true, false);
            await loadSubcategories();
        }else{
            fireMessage('Error deleting subcategory');
        }
    }catch(error){
        console.log(error);
        fireMessage('Error deleting subcategory');
    }
}

const categoryOptions = computed(() => {
    return categories.value.map(c => ({ label: c.name, value: c.id }));
});

function onCategoryDropdownChange(e){
    subcategoryForm.value.product_category_id = e.value;
}
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categories & Subcategories
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Categories</h3>
                        <Button label="Add Category" icon="fa fa-plus" @click="openCategoryDialog()" />
                    </div>

                    <DataTable :value="categories" :loading="loadingCategories" dataKey="id" responsiveLayout="scroll">
                        <Column field="id" header="ID" style="width: 80px" />
                        <Column field="name" header="Name" />
                        <Column field="translated" header="Translated" />
                        <Column header="Actions" style="width: 150px">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <Button icon="fa fa-pencil" class="p-button-sm p-button-rounded" @click="openCategoryDialog(slotProps.data)" />
                                    <Button icon="fa fa-trash" class="p-button-sm p-button-rounded p-button-danger" @click="confirmDeleteCategory(slotProps.data)" />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Subcategories</h3>
                        <Button label="Add Subcategory" icon="fa fa-plus" @click="openSubcategoryDialog()" />
                    </div>

                    <DataTable :value="subcategories" :loading="loadingSubcategories" dataKey="id" responsiveLayout="scroll">
                        <Column field="id" header="ID" style="width: 80px" />
                        <Column field="name" header="Name" />
                        <Column field="translated" header="Translated" />
                        <Column field="product_category_name" header="Category" />
                        <Column header="Actions" style="width: 150px">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <Button icon="fa fa-pencil" class="p-button-sm p-button-rounded" @click="openSubcategoryDialog(slotProps.data)" />
                                    <Button icon="fa fa-trash" class="p-button-sm p-button-rounded p-button-danger" @click="confirmDeleteSubcategory(slotProps.data)" />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>

        <Modal :show="categoryDialogVisible" @close="closeCategoryDialog">
            <div class="p-6 space-y-4">
                <h3 class="text-lg font-semibold mb-2">
                    {{ categoryForm.id ? 'Edit Category' : 'Add Category' }}
                </h3>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <InputText v-model="categoryForm.name" class="w-full" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Translated</label>
                    <InputText v-model="categoryForm.translated" class="w-full" />
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <Button label="Cancel" class="p-button-text" @click="closeCategoryDialog" />
                    <Button label="Save" @click="saveCategory" />
                </div>
            </div>
        </Modal>

        <Modal :show="subcategoryDialogVisible" @close="closeSubcategoryDialog">
            <div class="p-6 space-y-4">
                <h3 class="text-lg font-semibold mb-2">
                    {{ subcategoryForm.id ? 'Edit Subcategory' : 'Add Subcategory' }}
                </h3>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <InputText v-model="subcategoryForm.name" class="w-full" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Translated</label>
                    <InputText v-model="subcategoryForm.translated" class="w-full" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <InputText v-model="subcategoryForm.description" class="w-full" />
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <Dropdown
                        v-model="subcategoryForm.product_category_id"
                        :options="categories"
                        optionLabel="name"
                        optionValue="id"
                        placeholder="Select a category"
                        class="w-full"
                    />
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <Button label="Cancel" class="p-button-text" @click="closeSubcategoryDialog" />
                    <Button label="Save" @click="saveSubcategory" />
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
