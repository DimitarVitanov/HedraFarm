
<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Datatable from 'primevue/datatable';
import Column from 'primevue/column';
import Icon from '@/Components/V2/Icon.vue';
import Modal from '@/Components/Modal.vue';
import Editor from '@/Components/Editor.vue'

const page = usePage();
const logged_user = ref(page.props.user);
const loading = ref(false);

const blogs = ref([]);
const blog = ref({
    id: null,
    image:null,
    title:null,
    content:null,
    user_id:null,
})

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
    await fetchBlogs();
})

async function fetchBlogs(){
    try{
        loading.value = true
        const response = await fetch('/blogs/fetch');
        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }
        const data = await response.json();
        if(data.success){
            blogs.value = data.data
        }
        loading.value = false
    }catch(error){
        console.log(error);
        loading.value = false
        return []
    }
}

const checkPermission = async () => {
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1') {
        location.href = '/dashboard';
    }
};
const showBlogModal = ref(false)

const openBlogModal = (data = null) => {
    if(data){
        blog.value = data
    }
    showBlogModal.value = true
}
const closeBlogModal = () => {
    clearForm();
    showBlogModal.value = false
}
const clearForm = () =>{
   blog.value = {
        id: null,
        image:null,
        title:null,
        content:null,
        user_id:null,
    }
}
const saveBlog = () => {
    if(blog.value.id){
        updateBlog()
    }else{
        addBlog()
    }
}

const addBlog = async () =>{
    try{
        if(!blog.value.title || !blog.value.content){
            fireMessage('Please fill blog title and content')
            return
        }
        const formData = new FormData();
        formData.append('title', blog.value.title)
        formData.append('content', blog.value.content)
        formData.append('image', blog.value.image)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)

        const response = await fetch('/admin/blogs/store', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while saving the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('Blog saved successfully', true, false)
            closeBlogModal()
            fetchBlogs()
        }
    }catch(error){
        console.log(error)
    }
}

const updateBlog = async () =>{
    try{
        if(!blog.value.title || !blog.value.content){
            fireMessage('Please fill blog title and content')
            return
        }
        const formData = new FormData();
        formData.append('id', blog.value.id)
        formData.append('title', blog.value.title)
        formData.append('content', blog.value.content)
        formData.append('image', blog.value.image)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)

        const response = await fetch('/admin/blogs/update', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while saving the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('Blog updated successfully', true, false)
            closeBlogModal()
            fetchBlogs()
        }
    }catch(error){
        console.log(error)
    }
}


const removeBlog = async(id)=>{
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e5484d',
        cancelButtonColor: '#7b8a84',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
            deleteBlog(id)
        }
      })
}

const deleteBlog = async(id) => {
    try{
        const formData = new FormData();
        formData.append('id', id)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)
        const response = await fetch('/admin/blogs/delete', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while deleting the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('Blog deleted successfully', true, false)
            fetchBlogs()
        }else{
            fireMessage('An error occurred while deleting the data')
            return[]
        }
   } catch(error){
    console.error(error)
    return []
   }
}

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        blog.value.image = file;

        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            blog.value.image_url = reader.result;
        };
    }
};
</script>
<template>
    <Head title="Admin Blog"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="ha-head__title">
                <h1>Blog</h1>
                <span class="ha-count">{{ blogs.length }}</span>
            </div>
            <div class="ha-head__actions">
                <button type="button" class="ha-btn" @click="openBlogModal()"><Icon name="plus" /> Create Blog</button>
            </div>
        </template>

        <section class="ha-card ha-table-wrap">
            <Datatable stripedRows size="small" paginator :value="blogs" :rows="10" :loading="loading" scrollable>
                <template #empty>
                    <div class="ha-empty"><Icon name="file" /><strong>No Blogs Found</strong></div>
                </template>

                <Column field="id" header="ID" sortable>
                    <template #body="{data}">
                        <span class="ha-cell-muted">#{{ data.id }}</span>
                    </template>
                </Column>
                <Column field="image" header="Image" sortable>
                    <template #body="{data}">
                        <img v-if="data.image" :src="'/assets' + data.image" :alt="data.title" class="ha-thumb ha-thumb--lg" :title="data.image">
                        <span v-else class="ha-thumb ha-thumb--lg ha-thumb--empty"><Icon name="image" /></span>
                    </template>
                </Column>
                <Column field="title" header="Title" sortable>
                    <template #body="{data}">
                        <span class="ha-cell-title">{{ data.title }}</span>
                    </template>
                </Column>
                <Column field="short_description" header="Short Description" sortable>
                    <template #body="{data}">
                        <div class="ha-cell-clamp ha-cell-muted" style="max-width: 460px">{{ data.short_description }}</div>
                    </template>
                </Column>
                <Column field="actions" header="Actions">
                    <template #body="{data}">
                        <div class="ha-actions">
                            <button type="button" class="ha-icon-btn" title="Edit" @click="openBlogModal(data)"><Icon name="pencil" /></button>
                            <button type="button" class="ha-icon-btn ha-icon-btn--danger" title="Delete" @click="removeBlog(data.id)"><Icon name="trash" /></button>
                        </div>
                    </template>
                </Column>
            </Datatable>
        </section>

        <!--start:: Blog Modal -->
        <Modal :show="showBlogModal" @close="closeBlogModal" :maxWidth="'2xl'">
            <div class="ha-modal__head">
                <h3>{{ blog.id == '' ? 'Add Blog' : 'Edit Blog' }}</h3>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="closeBlogModal"><Icon name="x" /></button>
            </div>
            <div class="ha-modal__body">
                <div class="ha-field">
                    <label for="title" class="ha-label">Title</label>
                    <input type="text" class="ha-input" id="title" v-model="blog.title">
                </div>

                <div class="ha-field">
                    <label for="content" class="ha-label">Content</label>
                    <Editor :modelValue="blog.content" @update:modelValue="blog.content = $event" />
                </div>

                <div class="ha-field">
                    <label for="image" class="ha-label">Image</label>
                    <input type="file" class="ha-input" id="image" @change="handleImageUpload">
                </div>
            </div>
            <div class="ha-modal__foot">
                <button type="button" class="ha-btn ha-btn--ghost" @click="closeBlogModal">Cancel</button>
                <button type="button" class="ha-btn" @click="saveBlog()"><Icon name="check" /> Save</button>
            </div>
        </Modal>
        <!--end:: Blog Modal -->
    </AuthenticatedLayout>
</template>
