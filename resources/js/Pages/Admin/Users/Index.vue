
<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Datatable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
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
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
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
        <div class="users">
            <div class="col-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Users</h2>
                     <Button label="Add User" severity="danger" @click="openUserModal()"> </Button>
                </div>

                <div class="table-responsive pt-5">
                <!--start:: Users Table -->
                <Datatable stripedRows size="small" paginator :value="users" :rows="10" :loading="loading">
                    <Column field="is_active" header="Active" sortable>
                    <template #body="{data}">
                        <div :class="data.is_active ? 'bg-success is-active' : 'bg-danger is-active'">
                        </div>
                    </template>
                    </Column>
                    <Column field="name" header="Name" sortable></Column>
                    <Column field="email" header="Email" sortable></Column>
                    <Column field="actions" header="Actions">
                    <template #body="{data}">
                        <div class="d-flex align-items-center">
                            <Button label="Edit" @click="openUserModal(data)" severity="info"> <i class="fa fa-edit"></i></Button>
                            <Button label="Delete" @click="removeUser(data.id)" severity="danger" class="ms-2"> <i class="fa fa-trash"></i></Button>
                        </div>
                    </template>
                    </Column>

                </Datatable>
                <!--end:: Users Table-->
                </div>
            </div>
            <!--start:: User Modal -->
            <Modal :show="showUserModal" @close="closeUserModal" :maxWidth="'md'">
                <div class="p-4">
                    <h4> {{ add_user == '' ? 'Add User' : 'Edit User' }}</h4>
                    <div class="form-group mt-2">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" v-model="user.name">
                    </div>
                    <div class="form-group  mt-2">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" v-model="user.email">
                    </div>
                    <div class="form-group  mt-2">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" v-model="user.password">
                    </div>
                    <div class="form-group  mt-2">
                      <label for="team_id">Team</label>
                    <select class="form-control" id="team_id" v-model="user.team_id">
                        <option value="1">Admin</option>
                        <option value="2">Moderator</option>
                    </select>
                    </div>
                    <div class="form-group  my-3 d-flex justify-content-end ">
                    <label for="is_active">Is Active</label>
                    <input type="checkbox" class="form-check-input ms-2 cursor-pointer " id="is_active" v-model="user.is_active">
                    </div>
                    <div class="d-flex justify-content-end align-items-center  mt-2">
                        <Button label="Save" severity="success" @click="saveUser()"></Button>
                        <Button label="Cancel" class="ms-2" severity="secondary" @click="closeUserModal"></Button>
                    </div>
                </div>

            </Modal>

            <!--end:: User Modal-->

        </div>
    </AuthenticatedLayout>



</template>


<style setup>
.users{
    background-color: white;
    min-height: 92vh;
    padding-top:5rem;
}
.is-active{
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left:1rem;
}
</style>
