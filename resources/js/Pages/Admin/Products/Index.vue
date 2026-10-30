<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Icon from '@/Components/V2/Icon.vue';
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
    disscount: null,
    product_category_id: '',
    subcategories:[],
    main_image:'',
    gallery: [],
    is_active: true,
    show_trending:false,
    show_on_sale: false,
    show_best_seller:false,
    show_top_rated:false,
    show_popular:false,
});
const products = ref([]);
const loading = ref(false);
const subcategories = ref([]);

const categories = ref(false);

/* Table: search, category and status filters */
const LOW_STOCK = 10;
const placementFlags = [
    { field: 'show_trending', label: 'Trending' },
    { field: 'show_on_sale', label: 'On Sale' },
    { field: 'show_best_seller', label: 'Best Seller' },
    { field: 'show_top_rated', label: 'Top Rated' },
    { field: 'show_popular', label: 'Popular' },
];
const search = ref('');
const categoryFilter = ref('');
const statusFilter = ref('all');
const first = ref(0);

const rows = computed(() => products.value.map(item => ({
    ...item,
    stock: Number(item.quantity) || 0,
    final_price: item.disscount ? item.price - (item.price * item.disscount) / 100 : Number(item.price),
})));

const matchesStatus = (item, status) => ({
    all: true,
    active: !!item.is_active,
    inactive: !item.is_active,
    low: item.stock < LOW_STOCK,
}[status]);

const statusTabs = computed(() => [
    { value: 'all', label: 'All' },
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'low', label: 'Low stock' },
].map(tab => ({ ...tab, count: rows.value.filter(item => matchesStatus(item, tab.value)).length })));

const filteredProducts = computed(() => {
    const term = search.value.trim().toLowerCase();
    return rows.value.filter(item => {
        if (categoryFilter.value !== '' && String(item.product_category_id) !== String(categoryFilter.value)) return false;
        if (!matchesStatus(item, statusFilter.value)) return false;
        if (!term) return true;
        return [item.name, item.short_description, item.clean_description, item.product_category_name]
            .some(value => String(value || '').toLowerCase().includes(term));
    });
});

watch([search, categoryFilter, statusFilter], () => (first.value = 0));

const formatMoney = (value) => `${(Math.round((Number(value) || 0) * 100) / 100).toLocaleString('mk-MK', { maximumFractionDigits: 2 })} ден`;

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
    if (logged_user.value.team_id != '1' || logged_user.value.team_id != '2') {
        location.href = '/dashboard';
    }
}

/* Fetch Products */
async function fetchProducts(){
    try{
        loading.value = true
        const response = await fetch('/admin/products/fetch')
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
        disscount: null,
        product_category_id: '',
        main_image:'',
        gallery:[],
        is_active: false,
        show_trending:false,
        show_on_sale: false,
        show_best_seller:false,
        show_top_rated:false,
        show_popular:false,
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
    formData.append("disscount", product.value.disscount);
    formData.append("product_category_id", product.value.product_category_id);
    formData.append("is_active", product.value.is_active);
    formData.append("show_trending", product.value.show_trending);
    formData.append("show_on_sale", product.value.show_on_sale);
    formData.append("show_best_seller", product.value.show_best_seller);
    formData.append("show_top_rated", product.value.show_top_rated);
    formData.append("show_popular", product.value.show_popular);
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
        formData.append('disscount', product.value.disscount)
        formData.append('product_category_id', product.value.product_category_id)
        formData.append('is_active', product.value.is_active)
        formData.append('show_trending', product.value.show_trending)
        formData.append('show_on_sale', product.value.show_on_sale)
        formData.append('show_best_seller', product.value.show_best_seller)
        formData.append('show_top_rated', product.value.show_top_rated)
        formData.append('show_popular', product.value.show_popular)
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
        confirmButtonColor: '#e5484d',
        cancelButtonColor: '#7b8a84',
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
        <template #header>
            <div class="ha-head__title">
                <h1>Products</h1>
                <span class="ha-count">{{ products.length }}</span>
            </div>
            <div class="ha-head__actions">
                <button type="button" class="ha-btn" @click="openProductModal()"><Icon name="plus" /> Add Product</button>
            </div>
        </template>

        <section class="ha-card ha-table-wrap">
            <div class="ha-toolbar">
                <label class="ha-search">
                    <Icon name="search" />
                    <input v-model="search" type="search" class="ha-input" placeholder="Search by name or description" aria-label="Search products">
                </label>
                <select v-model="categoryFilter" class="ha-input ha-toolbar__select" aria-label="Category">
                    <option value="">All categories</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.translated }}</option>
                </select>
                <div class="ha-segment" role="group" aria-label="Status">
                    <button
                        v-for="tab in statusTabs"
                        :key="tab.value"
                        type="button"
                        :class="{ 'is-active': statusFilter === tab.value, 'is-warning': tab.value === 'low' && tab.count > 0 }"
                        @click="statusFilter = tab.value"
                    >
                        {{ tab.label }} <span>{{ tab.count }}</span>
                    </button>
                </div>
            </div>

            <DataTable
                :value="filteredProducts"
                :loading="loading"
                :paginator="true"
                :rows="10"
                :rowsPerPageOptions="[10,20,50]"
                v-model:first="first"
                dataKey="id"
                scrollable
                class="ha-products-table"
            >
                <template #empty>
                    <div class="ha-empty"><Icon name="box" />No products found</div>
                </template>
                <Column field="name" header="Product" sortable>
                    <template #body="{data}">
                        <div class="ha-product">
                            <img v-if="data.main_image" :src="data.main_image" :alt="data.name" class="ha-thumb">
                            <span v-else class="ha-thumb ha-thumb--empty" title="No Image"><Icon name="image" /></span>
                            <div class="ha-product__meta">
                                <span class="ha-cell-title">{{ data.name }}</span>
                                <span class="ha-product__desc">{{ data.short_description || data.clean_description }}</span>
                                <span class="ha-product__id">#{{ data.id }}</span>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column field="product_category_name" header="Category" sortable>
                    <template #body="{data}">
                        <span v-if="data.product_category_name" class="ha-chip">{{ data.product_category_name }}</span>
                    </template>
                </Column>
                <Column field="final_price" header="Price" sortable>
                    <template #body="{data}">
                        <div class="ha-price">
                            <span class="ha-money">{{ formatMoney(data.final_price) }}</span>
                            <span v-if="data.disscount" class="ha-price__old">
                                <s>{{ formatMoney(data.price) }}</s>
                                <span class="ha-badge ha-badge--danger ha-badge--plain">-{{ data.disscount }}%</span>
                            </span>
                        </div>
                    </template>
                </Column>
                <Column field="stock" header="Stock" sortable>
                    <template #body="{data}">
                        <span v-if="data.stock <= 0" class="ha-badge ha-badge--danger">Out of stock</span>
                        <span v-else-if="data.stock < LOW_STOCK" class="ha-badge ha-badge--warning">{{ data.stock }} left</span>
                        <span v-else class="ha-stock">{{ data.stock }}</span>
                    </template>
                </Column>
                <Column field="is_active" header="Status" sortable>
                    <template #body="{data}">
                        <span class="ha-status-dot" :class="{ 'is-on': data.is_active }">{{ data.is_active ? 'Active' : 'Inactive' }}</span>
                    </template>
                </Column>
                <Column header="Shown on">
                    <template #body="{data}">
                        <div class="ha-chips">
                            <span v-for="flag in placementFlags.filter(flag => data[flag.field])" :key="flag.field" class="ha-chip ha-chip--brand">{{ flag.label }}</span>
                            <span v-if="!placementFlags.some(flag => data[flag.field])" class="ha-cell-muted">-</span>
                        </div>
                    </template>
                </Column>
                <Column header="Actions">
                    <template #body="{data}">
                        <div class="ha-actions">
                            <button type="button" class="ha-icon-btn" title="Edit" @click="openProductModal(data)"><Icon name="pencil" /></button>
                            <button type="button" class="ha-icon-btn ha-icon-btn--danger" title="Delete" @click="removeProduct(data.id)"><Icon name="trash" /></button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </section>

        <!-- start Add/Edit Product Modal -->
        <Modal :show="showProductModal" @close="closeProductModal" :maxWidth="'2xl'">
            <div class="ha-modal__head">
                <h3>{{ product.id == '' ? 'Add Product' : 'Edit Product' }}</h3>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="closeProductModal()"><Icon name="x" /></button>
            </div>

            <div class="ha-modal__body">
                <div class="ha-grid ha-grid--2">
                    <div class="ha-field">
                        <label for="name" class="ha-label">Name</label>
                        <input type="text" class="ha-input" id="name" v-model="product.name">
                    </div>
                    <div class="ha-field">
                        <label for="short_description" class="ha-label">Short Description</label>
                        <input type="text" class="ha-input" id="short_description" v-model="product.short_description">
                    </div>
                </div>

                <div class="ha-field">
                    <label for="description" class="ha-label">Description</label>
                    <Editor :modelValue="product.description" @update:modelValue="product.description = $event" />
                </div>

                <div class="ha-grid ha-grid--5">
                    <div class="ha-field">
                        <label for="quantity" class="ha-label">Quantity</label>
                        <input type="number" class="ha-input" id="quantity" v-model="product.quantity">
                    </div>
                    <div class="ha-field">
                        <label for="price" class="ha-label">Price</label>
                        <input type="number" class="ha-input" id="price" v-model="product.price">
                    </div>
                    <div class="ha-field">
                        <label for="disscount" class="ha-label">Disscount</label>
                        <input type="number" class="ha-input" id="disscount" v-model="product.disscount">
                    </div>
                    <div class="ha-field">
                        <label for="product_category_id" class="ha-label">Category</label>
                        <select class="ha-input" id="product_category_id" v-model="product.product_category_id">
                            <option value="">Select Category</option>
                            <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.translated }}</option>
                        </select>
                    </div>
                    <div class="ha-field">
                        <label for="product_subcategory_id" class="ha-label">Subcategory</label>
                        <Multiselect placeholder="Select Subcategories" :options="subcategories" optionLabel="translated" optionValue="id" v-model="product.subcategories" />
                    </div>
                </div>

                <div class="ha-field">
                    <label for="main_image" class="ha-label">Main Image</label>
                    <input type="file" class="ha-input" id="main_image" @change="handleImageUpload">
                    <div class="ha-gallery" v-if="product.id && product.main_image" style="margin-top: 0.4rem">
                        <div class="ha-gallery__item">
                            <img :src="product.main_image" alt="Main Image">
                            <button type="button" class="ha-gallery__remove" title="Remove" @click="removeProduct(product.id, 'main_image')"><Icon name="x" /></button>
                        </div>
                    </div>
                </div>

                <hr class="ha-divider" style="margin: 0.4rem 0">

                <div>
                    <p class="ha-section-title">Show product on</p>
                    <div class="ha-toggles">
                        <label class="ha-switch ha-toggle" for="is_active">
                            Active
                            <input type="checkbox" id="is_active" v-model="product.is_active">
                        </label>
                        <label class="ha-switch ha-toggle">
                            Trending
                            <input type="checkbox" v-model="product.show_trending">
                        </label>
                        <label class="ha-switch ha-toggle">
                            On Sale
                            <input type="checkbox" v-model="product.show_on_sale">
                        </label>
                        <label class="ha-switch ha-toggle">
                            Best Seller
                            <input type="checkbox" v-model="product.show_best_seller">
                        </label>
                        <label class="ha-switch ha-toggle">
                            Top Rated
                            <input type="checkbox" v-model="product.show_top_rated">
                        </label>
                        <label class="ha-switch ha-toggle">
                            Popular
                            <input type="checkbox" v-model="product.show_popular">
                        </label>
                    </div>
                </div>

                <hr class="ha-divider" style="margin: 0.4rem 0">

                <div class="ha-field">
                    <p class="ha-section-title" style="margin-bottom: 0.4rem">Product Gallery</p>
                    <label for="gallery" class="ha-label">Gallery</label>
                    <input type="file" class="ha-input" id="gallery" multiple @change="handleGalleryUpload">
                    <div v-if="product.galleries && product.id" class="ha-gallery" style="margin-top: 0.4rem">
                        <div v-for="(image, index) in product.galleries" :key="index" class="ha-gallery__item">
                            <img :src="image.image" alt="Gallery Image">
                            <button type="button" class="ha-gallery__remove" title="Remove" @click="removeProduct(product.id, 'gallery', image.id)"><Icon name="x" /></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ha-modal__foot">
                <button type="button" class="ha-btn ha-btn--ghost" @click="closeProductModal()">Cancel</button>
                <button type="button" class="ha-btn" @click="saveProduct()"><Icon name="check" /> Save</button>
            </div>
        </Modal>
        <!-- end Add/Edit Product Modal-->
    </AuthenticatedLayout>
</template>
