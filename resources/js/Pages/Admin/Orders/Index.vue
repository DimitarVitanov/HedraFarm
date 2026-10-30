<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import  AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Icon from '@/Components/V2/Icon.vue';
import Modal from '@/Components/Modal.vue';
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
/* Edit order (quantities / removed items) - the invoice is generated from the saved values */
const showEditModal = ref(false)
const editing = ref(null)
const savingOrder = ref(false)

const money = (value) => `${(Math.round((Number(value) || 0) * 100) / 100).toLocaleString('mk-MK', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ден`

const openEditModal = (order) => {
    editing.value = {
        id: order.id,
        customer: `${order.first_name} ${order.last_name}`,
        email: order.email,
        status: order.status,
        items: (order.items || []).map(item => ({
            id: item.id,
            title: item.title,
            price: Number(item.price),
            quantity: Number(item.quantity),
            original: Number(item.quantity),
        })),
        discount: Number(order.discount || 0),
        delivery_price: Number(order.delivery_price || 0),
        originalSubtotal: Number(order.subtotal || 0),
        originalDiscount: Number(order.discount || 0),
        originalTotal: Number(order.total_price || 0),
    }
    showEditModal.value = true
}

const closeEditModal = () => {
    showEditModal.value = false
    editing.value = null
}

const editSubtotal = computed(() => editing.value
    ? editing.value.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
    : 0)

const editTotal = computed(() => {
    if (!editing.value) return 0
    const discount = Math.min(Number(editing.value.discount) || 0, editSubtotal.value)
    return editSubtotal.value - discount + (Number(editing.value.delivery_price) || 0)
})

const editChanged = computed(() => editing.value && Math.abs(editTotal.value - editing.value.originalTotal) > 0.005)

// Discount was calculated on the original cart - keep it in proportion to the new subtotal
const setQuantity = (item, quantity) => {
    item.quantity = Math.max(0, Math.floor(Number(quantity) || 0))
    const order = editing.value
    if (order.originalDiscount && order.originalSubtotal) {
        order.discount = Math.round(order.originalDiscount * editSubtotal.value / order.originalSubtotal * 100) / 100
    }
}

const saveOrder = async () => {
    const order = editing.value
    if (!order.items.some(item => item.quantity > 0)) {
        fireMessage('An order needs at least one item. Cancel the order instead.')
        return
    }
    savingOrder.value = true
    try {
        const formData = new FormData();
        formData.append('order_id', order.id);
        formData.append('items', JSON.stringify(order.items.map(item => ({ id: item.id, quantity: item.quantity }))));
        formData.append('discount', Number(order.discount) || 0);
        formData.append('delivery_price', Number(order.delivery_price) || 0);
        const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('_token', _token);
        const reponse = await fetch('/admin/orders/update', {
            method: 'POST',
            body: formData
        })
        if (!reponse.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await reponse.json();
        if (data.status == 'success') {
            closeEditModal();
            orders.value = await fetchOrders();
            fireMessage('Order updated successfully', true, false);
        } else {
            fireMessage(data.message || 'Error occurred while trying to update order', false, false);
        }
    } catch (error) {
        console.error('Error updating order:', error);
        fireMessage('Error occurred while trying to update order', false, false);
    } finally {
        savingOrder.value = false
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
        <template #header>
            <div class="ha-head__title">
                <h1>Orders</h1>
                <span class="ha-count">{{ orders.length }}</span>
            </div>
        </template>

        <section class="ha-card ha-table-wrap">
            <DataTable :value="orders" :paginator="true" :rows="10" :loading="loading" dataKey="id" scrollable>
                <template #empty>
                    <div class="ha-empty"><Icon name="receipt" />No orders yet</div>
                </template>
                <Column field="email" header="Email">
                    <template #body="{ data }">
                        <span class="ha-cell-title">{{ data.email }}</span>
                    </template>
                </Column>
                <Column field="phone" header="Phone">
                    <template #body="{ data }">
                        <span class="ha-nowrap">{{ data.phone }}</span>
                    </template>
                </Column>
                <Column field="address" header="Address"></Column>
                <Column field="postal_code" header="Postal Code"></Column>
                <Column field="city" header="City"></Column>
                <Column field="payment_method" header="Payment Method"></Column>
                <Column field="delivery_price" header="Delivery Price">
                    <template #body="{ data }">
                        <span class="ha-nowrap">{{ data.delivery_price }}</span>
                    </template>
                </Column>
                <Column field="total_price" header="Total">
                    <template #body="{ data }">
                        <span class="ha-money">{{ data.total_price }}</span>
                    </template>
                </Column>
                <Column field="status" header="Status">
                    <template #body="{ data }">
                        <span v-if="data.status == 'completed'" class="ha-badge ha-badge--success ha-badge--cap">completed</span>
                        <span v-if="data.status == 'pending'" class="ha-badge ha-badge--warning ha-badge--cap">{{data.status}}</span>
                        <span v-if="data.status == 'cancelled'" class="ha-badge ha-badge--danger ha-badge--cap">{{data.status}}</span>
                    </template>
                </Column>
                <Column field="created_at" header="Created At">
                    <template #body="{ data }">
                        <span class="ha-cell-muted ha-nowrap">{{ data.created_at }}</span>
                    </template>
                </Column>
                <Column field="actions" header="Actions">
                    <template #body="{ data }">
                        <div class="ha-actions">
                            <button v-if="data.status === 'pending'" type="button" class="ha-icon-btn" title="Approve Order" @click="approveOrder(data)"><Icon name="check" /></button>
                            <button v-if="data.status !== 'cancelled'" type="button" class="ha-icon-btn" title="Edit Order" @click="openEditModal(data)"><Icon name="pencil" /></button>
                            <a :href="'/admin/orders/' + data.id + '/invoice?preview=1'" target="_blank" rel="noopener" class="ha-icon-btn" title="Preview Invoice"><Icon name="eye" /></a>
                            <a :href="'/admin/orders/' + data.id + '/invoice'" class="ha-icon-btn" title="Download Invoice"><Icon name="download" /></a>
                            <button v-if="data.status !== 'cancelled'" type="button" class="ha-icon-btn ha-icon-btn--danger" title="Cancel Order" @click="cancelOrder(data)"><Icon name="x" /></button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </section>

        <!--start:: Edit Order Modal -->
        <Modal :show="showEditModal" @close="closeEditModal" maxWidth="xl">
            <template v-if="editing">
                <div class="ha-modal__head">
                    <div>
                        <h3>Edit Order #{{ String(editing.id).padStart(6, '0') }}</h3>
                        <p class="ha-hint">{{ editing.customer }} · {{ editing.email }}</p>
                    </div>
                    <button type="button" class="ha-icon-btn ha-icon-btn--plain" aria-label="Close" @click="closeEditModal"><Icon name="x" /></button>
                </div>

                <div class="ha-modal__body">
                    <p v-if="editing.status === 'completed'" class="ha-note">
                        <Icon name="alert" /> This order is already approved. Stock will be adjusted by the difference.
                    </p>

                    <div class="ha-order-items">
                        <div v-for="item in editing.items" :key="item.id" class="ha-order-item" :class="{ 'is-removed': item.quantity === 0 }">
                            <div class="ha-order-item__info">
                                <strong>{{ item.title }}</strong>
                                <span>
                                    {{ money(item.price) }} / pc
                                    <template v-if="item.quantity !== item.original"> · was {{ item.original }}</template>
                                </span>
                            </div>

                            <div v-if="item.quantity > 0" class="ha-stepper">
                                <button type="button" aria-label="Decrease" @click="setQuantity(item, item.quantity - 1)"><Icon name="minus" /></button>
                                <input type="number" min="0" :value="item.quantity" aria-label="Quantity" @change="setQuantity(item, $event.target.value)">
                                <button type="button" aria-label="Increase" @click="setQuantity(item, item.quantity + 1)"><Icon name="plus" /></button>
                            </div>
                            <button v-else type="button" class="ha-btn ha-btn--ghost ha-btn--sm" @click="setQuantity(item, item.original)">Undo</button>

                            <span class="ha-order-item__total">{{ item.quantity > 0 ? money(item.price * item.quantity) : 'Removed' }}</span>

                            <button v-if="item.quantity > 0" type="button" class="ha-icon-btn ha-icon-btn--danger" title="Remove item" @click="setQuantity(item, 0)"><Icon name="trash" /></button>
                            <span v-else></span>
                        </div>
                    </div>

                    <div class="ha-order-totals">
                        <div class="ha-order-totals__row">
                            <span>Subtotal</span>
                            <strong>{{ money(editSubtotal) }}</strong>
                        </div>
                        <div class="ha-order-totals__row">
                            <label for="edit_discount">Discount <small v-if="editing.originalDiscount">(scaled with the subtotal)</small></label>
                            <input id="edit_discount" type="number" min="0" step="0.01" class="ha-input ha-input--sm" v-model.number="editing.discount">
                        </div>
                        <div class="ha-order-totals__row">
                            <label for="edit_delivery">Delivery</label>
                            <input id="edit_delivery" type="number" min="0" step="0.01" class="ha-input ha-input--sm" v-model.number="editing.delivery_price">
                        </div>
                        <div class="ha-order-totals__row is-total">
                            <span>Total</span>
                            <div>
                                <small v-if="editChanged">was {{ money(editing.originalTotal) }}</small>
                                <strong>{{ money(editTotal) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ha-modal__foot">
                    <button type="button" class="ha-btn ha-btn--ghost" @click="closeEditModal">Cancel</button>
                    <button type="button" class="ha-btn" :disabled="savingOrder" @click="saveOrder"><Icon name="check" /> Save changes</button>
                </div>
            </template>
        </Modal>
        <!--end:: Edit Order Modal -->
    </AuthenticatedLayout>
</template>
