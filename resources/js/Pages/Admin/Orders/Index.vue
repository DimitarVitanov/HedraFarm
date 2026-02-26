<script setup>
import { ref, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import { data } from 'autoprefixer';
const page = usePage();
const logged_user = ref(page.props.user);

const orders = ref([])
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

const approveOrder = async (order)=>{
    try{
        const formData = new FormData();
        formData.append('order_id', order.id);
        formData.append('status', 'completed');
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const reponse = await fetch('/admin/orders/approve',{
            method:'POST',
            body: formData
        })
        if (!reponse.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await reponse.json();
        if(data.status == 'success'){
            fireMessage('Order approved successfully', true, true);
        }else{
            fireMessage(data.message || 'Error occurred while trying to approve order', false, false);

        }
    }catch(error){
        console.error('Error approving order:', error);
        fireMessage('Error occurred while trying to approve order', false, false);
    }
}

const cancelOrder = async (order)=>{
    try{
        const formData = new FormData();
        formData.append('order_id', order.id);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const reponse = await fetch('/admin/orders/cancel',{
            method:'POST',
            body: formData
        })
        if (!reponse.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await reponse.json();
        if(data.status == 'success'){
            fireMessage('Order cancelled successfully', true, true);
        }else{
            fireMessage(data.message || 'Error occurred while trying to cancel order', false, false);
        }
    }catch(error){
        console.error('Error cancelling order:', error);
        fireMessage('Error occurred while trying to cancel order', false, false);
    }
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
            <div class="col-12 px-6 py-5">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Orders</h2>
                </div>
            </div>
            <div class="col-12 px-6 py-2">
                <DataTable :value="orders" :paginator="true" :rows="10" :loading="loading" dataKey="id">
                    <Column field="email" header="Email"></Column>
                    <Column field="phone" header="Phone"></Column>
                    <Column field="address" header="Address"></Column>
                    <Column field="postal_code" header="Postal Code"></Column>
                    <Column field="city" header="City"></Column>
                    <Column field="payment_method" header="Payment Method"></Column>
                    <Column field="delivery_price" header="Delivery Price"></Column>
                    <Column field="total_price" header="Total"></Column>
                    <Column field="status" header="Status">
                    <template #body="{ data }">
                        <span v-if="data.status == 'completed'" class="badge badge-success">completed</span>
                        <span v-if="data.status == 'pending'" class="badge badge-warning d-flex align-items-center justify-content-center pb-2">{{data.status}}</span>
                        <span v-if="data.status == 'cancelled'" class="badge badge-danger d-flex align-items-center justify-content-center pb-2">{{data.status}}</span>
                    </template>
                    </Column>
                    <Column field="created_at" header="Created At"></Column>
                    <Column field="actions" header="Actions">
                        <template #body="{ data }">
                            <Button v-if="data.status === 'pending'" @click="approveOrder(data)" class="mr-2" title="Approve Order"><i class="fa fa-check"></i></Button>
                            <a :href="'/admin/orders/' + data.id + '/invoice'" class="p-button p-component mr-2 bg-invoice" title="Download Invoice"><i class="fa fa-download"></i></a>
                            <Button v-if="data.status !== 'cancelled'" @click="cancelOrder(data)" class="bg-danger mr-2" title="Cancel Order"><i class="fa fa-times"></i></Button>
                        </template>
                    </Column>
                </DataTable>
                </div>
        </div>

    </AuthenticatedLayout>

</template>

<style scoped>
.badge-danger{
    background-color: #dc3545;
    color: white;
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
    border-radius: 0.5rem;
}

.badge-warning{
    background-color: #ffc107;
    color: #212529;
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
    border-radius: 0.5rem;
}

.badge-success{
    background-color: #28a745;
    color: white;
    font-size: 0.75rem;
    padding: 0.4em 0.6em;
    border-radius: 0.5rem;
}

.mr-2{
    margin-right: 0.5rem;
}

.bg-danger{
    background-color: #dc3545 !important;
}

.bg-invoice{
    background-color: #6b8e23 !important;
    color: white !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>
