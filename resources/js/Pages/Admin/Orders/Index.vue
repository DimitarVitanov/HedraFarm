<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
const page = usePage();
const logged_user = ref(page.props.user);

const orders = ref([])

onMounted(async()=>{
    checkPermission();
    orders.value = await fetchOrders();
})

async function fetchOrders() {
    const reponse = await fetch('/admin/orders/fetch');
    if (!reponse.ok) {
        throw new Error('Network response was not ok');
    }
    const data = await reponse.json();
    if (data.status == 'error') {
        return [];
    }
    return data.data;
}
const checkPermission = ()=>{
    if (!logged_user.value || !logged_user.value.team_id) {
        return;
    }
    if (logged_user.value.team_id != '1' || logged_user.value.team_id != '2') {
        location.href = '/dashboard';
    }
}

</script>

<template>
    <Head title="Admin Orders"/>
    <AuthenticatedLayout>
        <div class="products pb-3 bg-white">
            <div class="col-12 px-6">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Orders</h2>
                </div>
            </div>
            <div class="col-12 px-6">
                <DataTable :value="orders" :paginator="true" :rows="10" :loading="loading" dataKey="id">
                    <Column field="email" header="Email"></Column>
                    <Column field="phone" header="Phone"></Column>
                    <Column field="address" header="Address"></Column>
                    <Column field="postal_code" header="Postal Code"></Column>
                    <Column field="city" header="City"></Column>
                    <Column field="payment_method" header="Payment Method"></Column>
                    <Column field="delivery_price" header="Delivery Price"></Column>
                    <Column field="total_price" header="Total"></Column>
                    <Column field="status" header="Status"></Column>
                    <Column field="created_at" header="Created At"></Column>
                    <Column field="actions" header="Actions">
                        <template #body="{ data }">
                            <Button><i class="fa fa-check"></i></Button>
                            <Button class="bg-danger ml-2"><i class="fa fa-cancel"></i></Button>
                            <Button class="bg-danger ml-2"><i class="fa fa-trash"></i></Button>

                        </template>
                    </Column>
                </DataTable>
                </div>
        </div>

    </AuthenticatedLayout>

</template>



<style scoped>

</style>
