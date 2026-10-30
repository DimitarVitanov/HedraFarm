
<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Datatable from 'primevue/datatable';
import Column from 'primevue/column';
import Icon from '@/Components/V2/Icon.vue';
import Modal from '@/Components/Modal.vue';
const page = usePage();
const logged_user = ref(page.props.user);
const users = ref([])
const user =ref({
    name: '',
    email: '',
    password: '',
    team_id: null,
    is_active: false,
})
const loading = ref(false);

onMounted(async()=>{
    checkPermission();
    users.value = await fetchUsers();
})

async function fetchUsers(){
    try{
        loading.value = true
        const response = await fetch('/admin/users/fetch')

        if(!response.ok){
            throw new Error('An error occurred while fetching the data')
        }

        const data = await response.json();
        if(data.success){
            loading.value = false
            return data.data
        }
        return []
    } catch(error){
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

const showUserModal = ref(false)

const openUserModal = (data = null) => {
    if(data){
        user.value = data
    }
    showUserModal.value = true
}
const closeUserModal = () => {
    clearForm();
    showUserModal.value = false
}

const clearForm = () =>{
    user.value = {
        name: '',
        email: '',
        password: '',
        team_id: null,
        is_active: false,
    }
}

const saveUser = () => {
    if(user.value.id){
        updateUser()
    }else{
        addUser()
    }
}

const addUser = async() => {
   try{
        if(user.value.name == '' || user.value.email == '' || user.value.password == '' || user.value.team_id == null){
            fireMessage('All fields are required')
            return
        }
        const formData = new FormData();
        formData.append('name', user.value.name)
        formData.append('email', user.value.email)
        formData.append('password', user.value.password)
        formData.append('team_id', user.value.team_id)
        formData.append('is_active', user.value.is_active)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)
        const response = await fetch('/admin/users/store', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while saving the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('User saved successfully', true, true)
        }else{
            fireMessage('An error occurred while saving the data')
            return[]
        }
   } catch(error){
    console.error(error)
    return []
   }
}

const updateUser = async() => {
   try{
        if(user.value.name == '' || user.value.email == '' || user.value.password == '' || user.value.team_id == null){
            fireMessage('All fields are required')
            return
        }
        const formData = new FormData();
        formData.append('id', user.value.id)
        formData.append('name', user.value.name)
        formData.append('email', user.value.email)
        formData.append('password', user.value.password)
        formData.append('team_id', user.value.team_id)
        formData.append('is_active', user.value.is_active)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)
        const response = await fetch('/admin/users/update', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while saving the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('User saved successfully', true, true)
        }else{
            fireMessage('An error occurred while saving the data')
            return[]
        }
   } catch(error){
    console.error(error)
    return []
   }
}

const removeUser = async(id)=>{
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
            deleteUser(id)
        }
      })
}

const deleteUser = async(id) => {
    try{
        const formData = new FormData();
        formData.append('id', id)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', csrfToken)
        const response = await fetch('/admin/users/delete', {
            method: 'POST',
            body: formData
        })
        if(!response.ok){
            throw new Error('An error occurred while deleting the data')
        }
        const data = await response.json()
        if(data.success){
            fireMessage('User deleted successfully', true, true)
        }else{
            fireMessage('An error occurred while deleting the data')
            return[]
        }
   } catch(error){
    console.error(error)
    return []
   }
}
</script>

<template>
    <Head title="Admin Users"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="ha-head__title">
                <h1>Users</h1>
                <span class="ha-count">{{ users.length }}</span>
            </div>
            <div class="ha-head__actions">
                <button type="button" class="ha-btn" @click="openUserModal()"><Icon name="plus" /> Add User</button>
            </div>
        </template>

        <!--start:: Users Table -->
        <section class="ha-card ha-table-wrap">
            <Datatable stripedRows size="small" paginator :value="users" :rows="10" :loading="loading" scrollable>
                <Column field="name" header="Name" sortable>
                    <template #body="{data}">
                        <div class="ha-user" style="padding: 0">
                            <span class="ha-avatar" style="width: 34px; height: 34px; font-size: 12.5px">{{ (data.name || '?').charAt(0) }}</span>
                            <span class="ha-cell-title">{{ data.name }}</span>
                        </div>
                    </template>
                </Column>
                <Column field="email" header="Email" sortable></Column>
                <Column field="is_active" header="Active" sortable>
                    <template #body="{data}">
                        <span class="ha-status-dot" :class="{ 'is-on': data.is_active }">{{ data.is_active ? 'Active' : 'Inactive' }}</span>
                    </template>
                </Column>
                <Column field="actions" header="Actions">
                    <template #body="{data}">
                        <div class="ha-actions">
                            <button type="button" class="ha-icon-btn" title="Edit" @click="openUserModal(data)"><Icon name="pencil" /></button>
                            <button type="button" class="ha-icon-btn ha-icon-btn--danger" title="Delete" @click="removeUser(data.id)"><Icon name="trash" /></button>
                        </div>
                    </template>
                </Column>
            </Datatable>
        </section>
        <!--end:: Users Table-->

        <!--start:: User Modal -->
        <Modal :show="showUserModal" @close="closeUserModal" :maxWidth="'md'">
            <div class="ha-modal__head">
                <h3>{{ add_user == '' ? 'Add User' : 'Edit User' }}</h3>
                <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="closeUserModal"><Icon name="x" /></button>
            </div>
            <div class="ha-modal__body">
                <div class="ha-field">
                    <label for="name" class="ha-label">Name</label>
                    <input type="text" class="ha-input" id="name" v-model="user.name">
                </div>
                <div class="ha-field">
                    <label for="email" class="ha-label">Email</label>
                    <input type="email" class="ha-input" id="email" v-model="user.email">
                </div>
                <div class="ha-field">
                    <label for="password" class="ha-label">Password</label>
                    <input type="password" class="ha-input" id="password" v-model="user.password">
                </div>
                <div class="ha-field">
                    <label for="team_id" class="ha-label">Team</label>
                    <select class="ha-input" id="team_id" v-model="user.team_id">
                        <option value="1">Admin</option>
                        <option value="2">Moderator</option>
                    </select>
                </div>
                <label class="ha-switch ha-toggle" for="is_active">
                    Is Active
                    <input type="checkbox" id="is_active" v-model="user.is_active">
                </label>
            </div>
            <div class="ha-modal__foot">
                <button type="button" class="ha-btn ha-btn--ghost" @click="closeUserModal">Cancel</button>
                <button type="button" class="ha-btn" @click="saveUser()"><Icon name="check" /> Save</button>
            </div>
        </Modal>
        <!--end:: User Modal-->
    </AuthenticatedLayout>
</template>
