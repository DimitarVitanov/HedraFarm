
<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Datatable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
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
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
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
        <div class="blog">
                        <div class="col-8 mx-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2>Blog</h2>
                                <Button label="Create Blog" severity="danger" @click="openBlogModal()"> </Button>
                            </div>
                        </div>
                        <div class="col-8 mx-auto mt-3">
                            <Datatable stripedRows size="small" paginator :value="blogs" :rows="10" :loading="loading">
                                <template #empty>
                                    <div class="text-center py-5">
                                        <h5> <strong> No Blogs Found </strong> </h5>
                                    </div>
                                </template>

                                <Column field="id" header="ID" sortable></Column>
                                <Column field="title" header="Title" sortable></Column>
                                <Column field="short_description" header="Short Description" sortable></Column>
                                <Column field="image" header="Image" sortable></Column>
                                <Column field="actions" header="Actions">
                                    <template #body="{data}">
                                        <div class="d-flex align-items-center">
                                            <Button severity="info" @click="openBlogModal(data)"><i class="fa fa-edit"></i> </Button>
                                            <Button  severity="danger" class="ms-2" @click="removeBlog(data.id)"><i class="fa fa-trash"></i></Button>
                                        </div>
                                    </template>
                                </Column>
                            </Datatable>
                        </div>

                        <!--start:: Blog Modal -->
                        <Modal :show="showBlogModal" @close="closeBlogModal" :maxWidth="'2xl'">
                            <div class="p-4">
                                <h4> {{ blog.id == '' ? 'Add Blog' : 'Edit Blog' }}</h4>

                                <div class="form-group mt-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" v-model="blog.title">
                                </div>

                                <div class="form-group mt-3">
                                    <label for="content">Content</label>
                                    <Editor :modelValue="blog.content" @update:modelValue="blog.content = $event" />
                                </div>

                                <div class="form-group mt-3">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control" id="image" @change="handleImageUpload">
                                </div>

                                <div class="d-flex justify-content-end align-items-center  mt-3">
                                    <Button label="Save" severity="success" @click="saveBlog()"></Button>
                                    <Button label="Cancel" class="ms-2" severity="secondary" @click="closeBlogModal"></Button>
                                </div>
                            </div>

                        </Modal>
                        <!--end:: Blog Modal -->
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.blog{
    background-color: white;
    min-height: 92vh;
    padding-top:5rem;
}
</style>
