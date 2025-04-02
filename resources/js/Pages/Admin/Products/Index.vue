<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Modal from '@/Components/Modal.vue';
import Editor from '@/Components/Editor.vue'
import Multiselect from 'primevue/multiselect';
const page = usePage();
const logged_user = ref(page.props.user);
const product = ref({
    id: '',
    name: '',
    description: '',
    quantity: '',
    price: '',
    product_category_id: '',
    subcategories:[],
    main_image:'',
    gallery: [],
    is_active: true,
    show_trending:false,
    show_on_sale: false,
    show_best_seller:false,
    show_featured:false,
});
const products = ref([]);
const loading = ref(false);
const subcategories = ref([]);

const categories = ref(false);

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

onMounted(async()=>{
    checkPermission();
    products.value = await fetchProducts()
    categories.value = await fetchCategories()
    subcategories.value = await fetchSubcategories()
})

const checkPermission = ()=>{
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1') {
        location.href = '/dashboard';
    }
}

/* Fetch Products */
async function fetchProducts(){
    try{
        loading.value = true
        const response = await fetch('/products/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            loading.value = false
            return data.data
        }
        return []
    }catch(error){
        console.log(error);
        loading.value = false
        return []
    }
}

/* Fetch Categories */
async function fetchCategories(){
    try{
        const response = await fetch('/product-categories/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            return data.data
        }
        return []
    }catch(error){
        console.log(error);
        return []
    }
}

/* Fetch Subcategories */
async function fetchSubcategories(){
    try{
        const response = await fetch('/product-subcategories/fetch')
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            return data.data
        }
        return []
    }catch(error){
        console.log(error);
        return []
    }
}

const showProductModal = ref(false)

const openProductModal = (data = null) => {
    if(data){

        product.value = {
        ...data,
        subcategories: data.subcategories.map(sub => sub.id)
    }
    }
    showProductModal.value = true
}

const closeProductModal = () => {
    showProductModal.value = false
    clearForm()
}
const clearForm = () =>{
    product.value = {
        id: '',
        name: '',
        short_description: '',
        description: '',
        quantity: '',
        price: '',
        product_category_id: '',
        main_image:'',
        gallery:[],
        is_active: false,
        show_trending:false,
        show_on_sale: false,
        show_best_seller:false,
        show_featured:false,
        show_trending:'',
        subcategories:[],
    }
}

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        product.value.main_image = file;

        // Preview the image before upload
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            product.value.image_url = reader.result; // Show preview
        };
    }
};

const handleGalleryUpload = (event) => {
    const files = event.target.files;
    if (!files.length) return;

    if (!Array.isArray(product.value.gallery)) {
        product.value.gallery = [];
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        const reader = new FileReader();
        reader.onload = (e) => {
            product.value.gallery.push({
                file,
                preview: e.target.result,
            });
        };
        reader.readAsDataURL(file);
    }
};

const saveProduct = () =>{
    if(product.value.id){
         updateProduct()
    }else{
        addProduct()
    }
}

const addProduct = async () => {
    try {
        console.log(product.value)
        if(!product.value.name || !product.value.description || !product.value.short_description || !product.value.quantity || !product.value.price || !product.value.product_category_id || !product.value.main_image){
            fireMessage('Please fill all required fields')
            return
        }
    const formData = new FormData();
    formData.append("name", product.value.name);
    formData.append("short_description", product.value.short_description);
    formData.append("description", product.value.description);
    formData.append("quantity", product.value.quantity);
    formData.append("price", product.value.price);
    formData.append("product_category_id", product.value.product_category_id);
    formData.append("is_active", product.value.is_active);
    formData.append("show_trending", product.value.show_trending);
    formData.append("show_on_sale", product.value.show_on_sale);
    formData.append("show_best_seller", product.value.show_best_seller);
    formData.append("show_featured", product.value.show_featured);
    formData.append("main_image", product.value.main_image);
    if (product.value.subcategories.length > 0) {
            const uniqueSubIds = [...new Set(product.value.subcategories.map(sub => typeof sub === 'object' ? sub.id : sub))];
            uniqueSubIds.forEach((id) => {
            formData.append('subcategories[]', id);
            });
    }
    const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append("_token", _token);
    // Append multiple gallery images
    console.log(product.value.gallery)
    if(product.value.gallery.length > 0){
        product.value.gallery.forEach((image, index) => {
        formData.append(`galleries[]`, image.file);
    });
    }
        const response = await fetch("/admin/products/store", {
            method: "POST",
            body: formData,
        });

        const data = await response.json();
        if (data.success) {
            fireMessage("Product saved successfully!", true, false);
            products.value = await fetchProducts()
            closeProductModal();
        } else {
            fireMessage("Error saving product.");
        }
    } catch (error) {
        console.log(error);
        fireMessage("Error saving product.");
    }
};

const updateProduct = async() =>{
    try{
        const formData = new FormData()
        formData.append('id', product.value.id)
        formData.append('name', product.value.name)
        formData.append('short_description', product.value.short_description)
        formData.append('description', product.value.description)
        formData.append('quantity', product.value.quantity)
        formData.append('price', product.value.price)
        formData.append('product_category_id', product.value.product_category_id)
        formData.append('is_active', product.value.is_active)
        formData.append('show_trending', product.value.show_trending)
        formData.append('show_on_sale', product.value.show_on_sale)
        formData.append('show_best_seller', product.value.show_best_seller)
        formData.append('show_featured', product.value.show_featured)
        formData.append('main_image', product.value.main_image)
        // Append multiple gallery images
        if(product.value.gallery && product.value.gallery.length > 0){
        product.value.gallery.forEach((image, index) => {
        formData.append(`galleries[]`, image.file);
        });
         }
        if (product.value.subcategories.length > 0) {
            const uniqueSubIds = [...new Set(product.value.subcategories.map(sub => typeof sub === 'object' ? sub.id : sub))];
            uniqueSubIds.forEach((id) => {
            formData.append('subcategories[]', id);
            });
        }
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', _token)
        const response = await fetch('/admin/products/update', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error(response.statusText)
        }
        const data = await response.json();
        if(data.success){
            products.value = await fetchProducts()
            closeProductModal()
            fireMessage('Product updated successfully', true, false)
        }else{
            fireMessage('An error occurred while updating the product')
        }
    }catch(error){
        console.log(error)
    }
}
const removeProduct = (id, type = null, index=null) =>{
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
            deleteProduct(id, type,index)
        }
    })
}
const deleteProduct = async(id, type,index) =>{
    try{
                const formData = new FormData()
                formData.append('id', id)
                formData.append('type', type)
                if(type == 'gallery'){
                    formData.append('gallery_id', index)
                }
                const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                formData.append('_token', _token)
                const response = await fetch('/admin/products/delete', {
                    method: 'POST',
                    body: formData
                })
                if(!response.ok){
                    throw new Error(response.statusText)
                }
                const data = await response.json();
                if(data.success){
                    fireMessage('Product deleted successfully', true, true)
                }else{
                    fireMessage('An error occurred while deleting the product')
                }
            }catch(error){
                console.log(error)
            }
}
</script>

<template>
    <Head title="Admin Products"/>
    <AuthenticatedLayout>
        <div class="products">
            <div class="col-12 px-6">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Products</h2>
                     <Button label="Add Product" severity="danger" @click="openProductModal()"> </Button>
                </div>
                <hr class="w-100 text-gray-300">

                <div class="table-responsive pt-4">
                        <DataTable :value="products" :loading="loading" :paginator="true" :rows="10" :rowsPerPageOptions="[5,10,20]">
                            <Column field="id" header="ID" sortable></Column>
                            <Column field="name" header="Name" sortable></Column>
                            <Column field="short_description" header="Short Description" sortable></Column>
                            <Column field="description" header="Description" sortable>
                            <template #body="{data}">
                                <div>{{data.clean_description}}</div>
                            </template>
                            </Column>
                            <Column field="quantity" header="Quantity" sortable></Column>
                            <Column field="price" header="Price" sortable>
                            <template #body="{data}">
                                <span>{{ data.price }} ден</span>
                            </template>
                            </Column>
                            <Column field="product_category_id" header="Category" sortable>
                            <template #body="{data}">
                                <div>{{ data.product_category_name }}</div>
                            </template>
                            </Column>
                            <Column field="is_active" header="Active" sortable>
                            <template #body="{data}">
                                <div :class="data.is_active ? 'bg-success dot' : 'bg-danger dot'">
                                </div>
                            </template>
                            </Column>
                            <Column field="show_trending" header="Trending" sortable>
                            <template #body="{data}">
                                <div :class="data.show_trending ? 'bg-success dot' : 'bg-danger dot'">
                                </div>
                            </template>
                            </Column>
                            <Column field="show_on_sale" header="On Sale" sortable>
                            <template #body="{data}">
                                <div :class="data.show_on_sale ? 'bg-success dot' : 'bg-danger dot'">
                                </div>
                            </template>
                            </Column>
                            <Column field="show_best_seller" header="Best Seller" sortable>
                            <template #body="{data}">
                                <div :class="data.show_best_seller ? 'bg-success dot' : 'bg-danger dot'">
                                </div>
                            </template>
                            </Column>
                            <Column field="show_featured" header="Featured" sortable>
                            <template #body="{data}">
                                <div :class="data.show_featured ? 'bg-success dot' : 'bg-danger dot'">
                                </div>
                            </template>
                            </Column>
                            <Column field="main_image" header="Main Image" sortable>
                            <template #body="{data}">
                                <img v-if="data.main_image" :src="data.main_image" alt="Main Image" class="img-fluid" style="width: 50px; height: 50px;">
                                <div v-else>
                                    No Image
                                </div>
                            </template>
                            </Column>
                            <Column field="action" header="Action" >
                            <template #body="{data}">
                                <div class="d-flex align-items-center">
                                    <Button  @click="openProductModal(data)" severity="info"> <i class="fa fa-edit"> </i></Button>
                                    <Button  @click="removeProduct(data.id)" severity="danger" class="ms-2"><i class="fa fa-trash"></i></Button>
                                </div>
                            </template>
                            </Column>
                        </DataTable>
                </div>
            </div>
        </div>

        <!-- start Add/Edit Product Modal -->
        <Modal :show="showProductModal" @close="closeProductModal" :maxWidth="'2xl'">
            <div class="p-4">
                <h4> {{ product.id == '' ? 'Add Product' : 'Edit Product' }}</h4>
            <div class="form-group mt-2">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" v-model="product.name">
            </div>

            <div class="form-group mt-2">
                <label for="short_description">Short Description</label>
                <input type="text" class="form-control" id="short_description" v-model="product.short_description">
            </div>

            <div class="form-group mt-3">
                <label for="description">Description</label>
                <Editor :modelValue="product.description" @update:modelValue="product.description = $event" />
            </div>
            <div class="row mt-2">
                <div class="col-12 col-md-3">
                    <div class="form-group mt-2">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" v-model="product.quantity">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group mt-2">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" v-model="product.price">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group mt-2">
                        <label for="product_category_id">Category</label>
                        <select class="form-control" id="product_category_id" v-model="product.product_category_id">
                            <option value="">Select Category</option>
                            <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.translated }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group mt-2">
                        <label for="product_subcategory_id">Subcategory</label>
                        <br>
                        <Multiselect class="col-md-12" placeholder="Select Subcategories" :options="subcategories" optionLabel="translated" optionValue="id" v-model="product.subcategories" />
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="main_image">Main Image</label>
                <input type="file" class="form-control" id="main_image" @change="handleImageUpload">
                <div class="pt-2" v-if="product.id && product.main_image" @click="removeProduct(product.id, 'main_image')">
                    <i class="fa fa-remove cursor-pointer text-danger"></i>
                    <img :src="product.main_image" alt="Main Image" class="img-fluid" style="width: 100px; height: 100px;">
                </div>
            </div>
            <h5 class="mt-4">Show product on</h5>
            <hr class="w-100 text-gray-300">
            <div class="row">
                <div class="form-group col-12 col-md-2">
                    <label for="is_active">Active</label>
                    <input type="checkbox" class="form-check-input ms-2" id="is_active" v-model="product.is_active">
                </div>
                <div class="form-group col-12 col-md-2">
                    <label for="show_trending">Trending</label>

                    <input type="checkbox"  class="form-check-input ms-2"  v-model="product.show_trending">
                </div>
                <div class="form-group col-12 col-md-2">
                    <label for="show_on_sale">On Sale</label>
                    <input type="checkbox"   class="form-check-input ms-2"  v-model="product.show_on_sale">
                </div>
                <div class="form-group col-12 col-md-2">
                    <label for="show_best_seller">Best Seller</label>
                    <input type="checkbox" class="form-check-input ms-2"  v-model="product.show_best_seller">
                </div>
                <div class="form-group col-12 col-md-2">
                    <label for="show_featured">Featured</label>
                    <input type="checkbox"  class="form-check-input ms-2" v-model="product.show_featured">
                </div>
            </div>

            <div class="mt-5">
                <h5>Product Gallery</h5>
                <hr class="w-100 text-gray-300">
            </div>

            <div class="form-group mt-2">
                <label for="gallery">Gallery</label>
                <input type="file" class="form-control" id="gallery" multiple  @change="handleGalleryUpload">
                <div v-if="product.galleries && product.id" class="mt-2 d-flex">
                        <div  v-for="(image, index) in product.galleries" :key="index" class="me-2">
                            <div @click="removeProduct(product.id, 'gallery', image.id)">
                                <i class="fa fa-remove cursor-pointer text-danger"></i>
                            </div>
                            <img :src="image.image" alt="Gallery Image" class="img-fluid" style="width: 100px; height: 100px;">
                        </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <Button label="Save" @click="saveProduct()" severity="success" />
                <Button label="Cancel" severity="secondary" class="ms-2" @click="closeProductModal()" />
            </div>

        </div>

        </Modal>

        <!-- end Add/Edit Product Modal-->
    </AuthenticatedLayout>


</template>

<style setup>
.products{
    background-color: white;
    min-height: 92vh;
    padding-top:5rem;
}
.dot{
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left:1rem;
}
</style>
