<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from 'primevue';
import Modal from '@/Components/Modal.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
const page = usePage();
const logged_user = ref(page.props.user);
const sliders = ref([])
const showAddSlideModal = ref(false)
const slide = ref({
    id:'',
    title: '',
    description: '',
    image: null,
    image_url: '',
    is_active: true,
    is_deleted: false,
})
const loading = ref(false);

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

onMounted(async ()=>{
    checkPermission();
    sliders.value = await fetchSliders();
})

const openSlideModal = (data) => {
    if(data){
        slide.value = data
    }
    showAddSlideModal.value = true
}

const closeSlideModal = () => {
    clearForm()
    showAddSlideModal.value = false
}

const clearForm = () => {
    slide.value = {
        id:'',
        title: '',
        description: '',
        image: null,
        image_url: '',
        is_active: true,
        is_deleted: false,
    }
}

const saveSlide = ()=>{
    if(slide.value.id == ''){
        addSlide()
    }else{
        updateSlide()
    }
}

const addSlide = async () => {
    try {
        const formData = new FormData()
        formData.append('title', slide.value.title)
        formData.append('description', slide.value.description)
        formData.append('subtitle', slide.value.subtitle)
        formData.append('price', slide.value.price)
        formData.append('image', slide.value.image)

        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', _token)

        const response = await fetch('/admin/sliders/store', {
            method: 'POST',
            body: formData
        })
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        if(data.success){
            fireMessage(data.message, true, true)
        }else{
            fireMessage(data.message)
        }
    } catch (error) {
        console.error(error)
        fireMessage('An error occurred while adding slide')
    }
}

const updateSlide =async () =>{
    try{
        if(slide.value.title == '' || slide.value.description == ''){
            fireMessage('Title and description are required fields')
            return
        }
        const formData = new FormData()
        formData.append('id', slide.value.id)
        formData.append('title', slide.value.title)
        formData.append('description', slide.value.description)
        formData.append('subtitle', slide.value.subtitle)
        formData.append('price', slide.value.price)
        formData.append('image', slide.value.image)

        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', _token)

        const response = await fetch('/admin/sliders/update', {
            method: 'POST',
            body: formData
        })
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        if(data.success){
            fireMessage(data.message, true, true)
        }else{
            fireMessage(data.message)
        }
    }catch(error){
        console.error(error)
        fireMessage('An error occurred while updating slide')
    }
}

const removeSlider = (id) => {
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
            deleteSlider(id)
        }
    })
}

const deleteSlider = async(id) => {
    try{
        const formData = new FormData();
        formData.append('id', id)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)
        const response = await fetch('/admin/sliders/delete', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while deleting the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('Slider deleted successfully', true, true)
        }else{
            fireMessage('An error occurred while deleting the data')
            return[]
        }
    } catch(error){
        console.error(error)
        return []
    }
}

async function fetchSliders() {
    try {
        loading.value = true
        const response = await fetch('/sliders/fetch')
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        if(data.success){
            loading.value = false
            return data.data
        }
        loading.value = false
        return []
    } catch (error) {
        console.error(error)
        loading.value  = false
        return []
    }
}

const checkPermission = async () => {
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1' || logged_user.value.team_id != '2') {
        location.href = '/dashboard';
    }
};
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        slide.value.image = file;

        // Preview the image before upload
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            slide.value.image_url = reader.result; // Show preview
        };
    }
};
</script>
<template>
    <Head title="Admin Slider"/>
    <AuthenticatedLayout>
    <div class="slider">
        <div class="col-8 mx-auto">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Slider</h2>
                <Button label="Add Slide" severity="danger" @click="openSlideModal()" />
            </div>
            <hr class="w-100 text-gray-300">

        <!--start:: Sliders Table -->
        <div class="table-responsive pt-5">
        <DataTable stripedRows size="small" paginator :value="sliders" :rows="10" :loading="loading">
            <Column field="title" header="Title" />
            <Column field="subtitle" header="Subtitle" />
            <Column field="description" header="Description" />
            <Column field="price" header="Price" />
            <Column field="image" header="Image">
                <template #body="{data}">
                    <img :src="data.image" alt="Slider Image" style="width: 100px; height: 100px" />
                </template>
            </Column>
            <Column header="Action">
                <template #body="{data}">
                    <Button severity="info" @click="openSlideModal(data)"><i class="fa fa-edit"></i></Button>
                    <Button label="Delete" @click="removeSlider(data.id)" severity="danger" class="ms-2"><i class="fa fa-trash"></i></Button>
                </template>
            </Column>

        </DataTable>
        </div>
    </div>
        <!--end:: Sliders Table-->

        <!--start:: Add Slide Modal-->
        <Modal :show="showAddSlideModal" @close="closeSlideModal()" :maxWidth="'md'">
            <div class="p-4">
                <h4 class="text-center pb-3"> {{ slide.id == '' ? 'Add Slide' : 'Edit Slide' }}</h4>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" v-model="slide.title">
                </div>
                <div class="form-group mt-3">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" class="form-control" id="subtitle" v-model="slide.subtitle">
                </div>
                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" v-model="slide.description"></textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="price">Price</label>
                    <input type="text" class="form-control w-30" id="price" v-model="slide.price">
                </div>
                <br/>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" @change="handleImageUpload">
                </div>

                <div class="d-flex justify-content-end align-items-center mt-3">
                    <Button label="Save" severity="success" @click="saveSlide()" />
                    <Button label="Cancel"  class="ms-2" severity="secondary" @click="closeSlideModal()" />
                </div>
                </div>
        </Modal>
        <!--end: Add Slide Modal-->


    </div>
    </AuthenticatedLayout>


</template>

<style scoped>
.slider{
    background-color: white;
    min-height: 92vh;
    padding-top: 5rem;
}
</style>
