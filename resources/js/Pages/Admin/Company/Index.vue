<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from 'primevue';
import Editor from '@/Components/Editor.vue'

const page = usePage();
const logged_user = ref(page.props.user);
const company = ref({})
const loading = ref(false)

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

onMounted(async() => {
    checkPermission();
    company.value = await fetchCompanyInfo();
    console.log(company.value)
})

const checkPermission = async () => {
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1') {
        location.href = '/dashboard';
    }
};

async function fetchCompanyInfo() {
    try {
        loading.value = true
        const response = await fetch('/company/fetch')
        if (!response.ok) {
            throw new Error(response.statusText)
        }
        const data = await response.json()
        if(data.success){
            loading.value = false
            return data.data
        }
        return []
    } catch (error) {
        console.error(error)
        loading.value = false
        return []
    }
}

const saveCompanyInfo = async () =>{
    try{
        const formData = new FormData()
        if(company.value.id != null){
            formData.append('id', company.value.id)
        }
        if(company.value.name != null){
            formData.append('name', company.value.name)
        }
        if(company.value.email != null){
            formData.append('email', company.value.email)
        }
        if(company.value.phone != null){
            formData.append('phone', company.value.phone)
        }
        if(company.value.address != null){
            formData.append('address', company.value.address)
        }
        if(company.value.worktime != null){
            formData.append('worktime', company.value.worktime)
        }
        if(company.value.quote != null){
            formData.append('quote', company.value.quote)
        }
        if(company.value.facebook_acc_link != null){
            formData.append('facebook_acc_link', company.value.facebook_acc_link)
        }
        if(company.value.instagram_acc_link != null){
            formData.append('instagram_acc_link', company.value.instagram_acc_link)
        }
        if(company.value.linkedin_acc_link != null){
            formData.append('linkedin_acc_link', company.value.linkedin_acc_link)
        }
        if(company.value.show_facebook != null){
            formData.append('show_facebook', company.value.show_facebook)
        }
        if(company.value.show_instagram != null){
            formData.append('show_instagram', company.value.show_instagram)
        }
        if(company.value.show_linkedin != null){
            formData.append('show_linkedin', company.value.show_linkedin)
        }
        if(company.value.about != null){
            formData.append('about', company.value.about)
        }

        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        formData.append('_token', _token)
        const response = await fetch('/admin/company/store', {
            method: 'POST',
            body: formData
        })

        console.log(response)
        if(!response.ok){
            throw new Error(response.statusText)
        }
        const data = await response.json();
        if(data.success){
            fireMessage(data.message, true, false)
        }
    }catch(error){
        console.error(error)
        return []
    }
}
</script>


<template>

    <Head title="Company" />
    <AuthenticatedLayout>
        <div class="header pb-10">
                    <!-- preloader -->
            <div v-if="loading" class="preloader">
                <div class="loader-ripple">
                    <div></div>
                    <div></div>
                </div>
            </div>

            <div v-else>
            <div class="col-8 mx-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Company</h2>
                </div>
                <hr class="w-100 text-gray-300">
            </div>

            <div class="company-form col-8 mx-auto">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="form-group">
                            <label for="about">About</label>
                            <Editor :modelValue="company.about" @update:modelValue="company.about = $event" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" v-model="company.name">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" v-model="company.email">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" v-model="company.phone">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" v-model="company.address">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="worktime">Worktime</label>
                            <input type="text" class="form-control" id="worktime" v-model="company.worktime">
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="quote">Quote</label>
                            <input type="text" class="form-control" id="quote" v-model="company.quote">
                        </div>
                    </div>

                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="facebook">Facebook</label>
                                <input type="text" class="form-control" id="facebook" v-model="company.facebook_acc_link">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="text" class="form-control" id="instagram" v-model="company.instagram_acc_link">
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="linkedin_acc_link">LinkedIn</label>
                                <input type="text" class="form-control" id="linkedin_acc_link" v-model="company.linkedin_acc_link">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="my-3">
                            <h4> Permissions </h4>
                            <hr class="w-100 text-gray-300">
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group ">
                                <label for="show_instagram" class="text-danger">Show Instagram</label>
                                <input type="checkbox" class="ms-2 form-check-input" id="show_instagram" v-model="company.show_instagram">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="show_facebook"  class="text-danger">Show Facebook</label>
                                <input type="checkbox"  class="ms-2 form-check-input" id="show_facebook" v-model="company.show_facebook">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="show_linkedin" class="text-danger">Show LinkedIn</label>
                                <input type="checkbox" class="ms-2 form-check-input" id="show_linkedin" v-model="company.show_linkedin">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <Button label="Save" @click="saveCompanyInfo()" severity="success" />
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>

<style scoped>
.header {
    background-color: white;
    min-height: 92vh;
    padding-top: 5rem;
}
</style>
