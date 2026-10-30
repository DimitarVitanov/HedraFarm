<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Icon from '@/Components/V2/Icon.vue';
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
        <template #header>
            <div class="ha-head__title">
                <h1>Company</h1>
            </div>
            <div class="ha-head__actions" v-if="!loading">
                <button type="button" class="ha-btn" @click="saveCompanyInfo()"><Icon name="check" /> Save</button>
            </div>
        </template>

        <!-- preloader -->
        <div v-if="loading" class="ha-card ha-loader">
            <span class="ha-spinner" aria-label="Loading"></span>
        </div>

        <div v-else class="ha-stack company-form">
            <section class="ha-card">
                <div class="ha-card__head"><h2><Icon name="file" /> About</h2></div>
                <div class="ha-card__body">
                    <Editor :modelValue="company.about" @update:modelValue="company.about = $event" />
                </div>
            </section>

            <section class="ha-card">
                <div class="ha-card__head"><h2><Icon name="building" /> Details</h2></div>
                <div class="ha-card__body">
                    <div class="ha-grid ha-grid--3">
                        <div class="ha-field">
                            <label for="name" class="ha-label">Name</label>
                            <input type="text" class="ha-input" id="name" v-model="company.name">
                        </div>
                        <div class="ha-field">
                            <label for="email" class="ha-label">Email</label>
                            <input type="email" class="ha-input" id="email" v-model="company.email">
                        </div>
                        <div class="ha-field">
                            <label for="phone" class="ha-label">Phone</label>
                            <input type="text" class="ha-input" id="phone" v-model="company.phone">
                        </div>
                        <div class="ha-field">
                            <label for="address" class="ha-label">Address</label>
                            <input type="text" class="ha-input" id="address" v-model="company.address">
                        </div>
                        <div class="ha-field">
                            <label for="worktime" class="ha-label">Worktime</label>
                            <input type="text" class="ha-input" id="worktime" v-model="company.worktime">
                        </div>
                        <div class="ha-field">
                            <label for="quote" class="ha-label">Quote</label>
                            <input type="text" class="ha-input" id="quote" v-model="company.quote">
                        </div>
                    </div>
                </div>
            </section>

            <section class="ha-card">
                <div class="ha-card__head"><h2><Icon name="instagram" /> Social</h2></div>
                <div class="ha-card__body">
                    <div class="ha-grid ha-grid--3">
                        <div class="ha-field">
                            <label for="facebook" class="ha-label">Facebook</label>
                            <input type="text" class="ha-input" id="facebook" v-model="company.facebook_acc_link">
                        </div>
                        <div class="ha-field">
                            <label for="instagram" class="ha-label">Instagram</label>
                            <input type="text" class="ha-input" id="instagram" v-model="company.instagram_acc_link">
                        </div>
                        <div class="ha-field">
                            <label for="linkedin_acc_link" class="ha-label">LinkedIn</label>
                            <input type="text" class="ha-input" id="linkedin_acc_link" v-model="company.linkedin_acc_link">
                        </div>
                    </div>

                    <hr class="ha-divider">

                    <p class="ha-section-title"><Icon name="shield" /> Permissions</p>
                    <div class="ha-toggles">
                        <label for="show_instagram" class="ha-switch ha-toggle">
                            Show Instagram
                            <input type="checkbox" id="show_instagram" v-model="company.show_instagram">
                        </label>
                        <label for="show_facebook" class="ha-switch ha-toggle">
                            Show Facebook
                            <input type="checkbox" id="show_facebook" v-model="company.show_facebook">
                        </label>
                        <label for="show_linkedin" class="ha-switch ha-toggle">
                            Show LinkedIn
                            <input type="checkbox" id="show_linkedin" v-model="company.show_linkedin">
                        </label>
                    </div>
                </div>
            </section>

            <div class="ha-head__actions" style="justify-content: flex-end">
                <button type="button" class="ha-btn" @click="saveCompanyInfo()"><Icon name="check" /> Save</button>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
