<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Components/V2/Icon.vue';
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
        confirmButtonColor: '#e5484d',
        cancelButtonColor: '#7b8a84',
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
    if (logged_user.value.team_id != '1' && logged_user.value.team_id != '2') {
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
        <template #header>
            <div class="ha-head__title">
                <h1>Slider</h1>
                <span class="ha-count">{{ sliders.length }}</span>
            </div>
            <div class="ha-head__actions">
                <button type="button" class="ha-btn" @click="openSlideModal()"><Icon name="plus" /> Add Slide</button>
            </div>
        </template>

        <!--start:: Sliders Table -->
        <section class="ha-card ha-table-wrap">
            <DataTable stripedRows size="small" paginator :value="sliders" :rows="10" :loading="loading" scrollable>
                <template #empty>
                    <div class="ha-empty"><Icon name="image" />No slides yet</div>
                </template>
                <Column field="image" header="Image">
                    <template #body="{data}">
                        <img :src="data.image" alt="Slider Image" class="ha-thumb ha-thumb--lg" style="object-fit: contain; background: #fff" />
                    </template>
                </Column>
                <Column field="title" header="Title">
                    <template #body="{data}">
                        <span class="ha-cell-title">{{ data.title }}</span>
                    </template>
                </Column>
                <Column field="subtitle" header="Subtitle" />
                <Column field="description" header="Description">
                    <template #body="{data}">
                        <div class="ha-cell-clamp ha-cell-muted">{{ data.description }}</div>
                    </template>
                </Column>
                <Column field="price" header="Price">
                    <template #body="{data}">
                        <span class="ha-money">{{ data.price }}</span>
                    </template>
                </Column>
                <Column header="Action">
                    <template #body="{data}">
                        <div class="ha-actions">
                            <button type="button" class="ha-icon-btn" title="Edit" @click="openSlideModal(data)"><Icon name="pencil" /></button>
                            <button type="button" class="ha-icon-btn ha-icon-btn--danger" title="Delete" @click="removeSlider(data.id)"><Icon name="trash" /></button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </section>
        <!--end:: Sliders Table-->

        <!--start:: Add Slide Modal-->
        <Modal :show="showAddSlideModal" @close="closeSlideModal()" :maxWidth="'md'">
            <div class="ha-modal__head">
                <h3>{{ slide.id == '' ? 'Add Slide' : 'Edit Slide' }}</h3>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="closeSlideModal()"><Icon name="x" /></button>
            </div>
            <div class="ha-modal__body">
                <div class="ha-field">
                    <label for="title" class="ha-label">Title</label>
                    <input type="text" class="ha-input" id="title" v-model="slide.title">
                </div>
                <div class="ha-field">
                    <label for="subtitle" class="ha-label">Subtitle</label>
                    <input type="text" class="ha-input" id="subtitle" v-model="slide.subtitle">
                </div>
                <div class="ha-field">
                    <label for="description" class="ha-label">Description</label>
                    <textarea class="ha-input" id="description" v-model="slide.description"></textarea>
                </div>
                <div class="ha-field">
                    <label for="price" class="ha-label">Price</label>
                    <input type="text" class="ha-input" id="price" v-model="slide.price" style="max-width: 200px">
                </div>
                <div class="ha-field">
                    <label for="image" class="ha-label">Image</label>
                    <input type="file" class="ha-input" id="image" @change="handleImageUpload">
                </div>
            </div>
            <div class="ha-modal__foot">
                <button type="button" class="ha-btn ha-btn--ghost" @click="closeSlideModal()">Cancel</button>
                <button type="button" class="ha-btn" @click="saveSlide()"><Icon name="check" /> Save</button>
            </div>
        </Modal>
        <!--end: Add Slide Modal-->
    </AuthenticatedLayout>
</template>
