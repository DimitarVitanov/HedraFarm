<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { useCart } from '@/utils/useCart';
import { computed } from 'vue';

const { cart, removeFromCart, updateQuantity, totalPrice, subtotal } = useCart()

// Calculate the total discount amount (product discounts + coupon discount)
const totalDiscount = computed(() => {
  // Product discounts
  const productDiscountAmount = cart.items.reduce((sum, item) => {
    if (item.disscount) {
      const discountAmount = (item.price * item.disscount / 100) * item.quantity
      return sum + discountAmount
    }
    return sum
  }, 0)
  
  // Add coupon discount
  return productDiscountAmount + cart.discount
})

// Calculate the subtotal before any discounts
const subtotalBeforeDiscounts = computed(() => {
  return cart.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
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

const order=ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    municipality: '',
    postalCode: '',
    country: 'Северна Македонија',
    additionalDescription: ''
})

const createOrder = async() =>{
    if(order.value.firstName === '' || order.value.lastName === '' || order.value.email === '' || order.value.phone === '' || order.value.address === '' || order.value.city === '' || order.value.postalCode === ''){
        fireMessage('Пополнете ги сите полиња', false ,false)
        return
    }

    const formData = new FormData();
    formData.append('firstName', order.value.firstName);
    formData.append('lastName', order.value.lastName);
    formData.append('email', order.value.email);
    formData.append('phone', order.value.phone);
    formData.append('address', order.value.address);
    formData.append('city', order.value.city);
    formData.append('municipality', order.value.municipality);
    formData.append('postalCode', order.value.postalCode);
    formData.append('country', order.value.country);
    formData.append('additionalDescription', order.value.additionalDescription);
    formData.append('cart', JSON.stringify(cart));
    formData.append('deliveryPrice', totalPrice.value < 2000 ? 200 : 0);
    formData.append('discount', totalDiscount.value);
    formData.append('totalPrice', totalPrice.value + (totalPrice.value < 2000 ? 200 : 0));
    formData.append('paymentMethod', 'cash');
    formData.append('status', 'pending');

    const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', _token);

    const reponse = await fetch('/api/store/make-order', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': _token,
            'Accept': 'application/json',
        },
    });
    const data = await reponse.json();
    if(data.status === 'success'){
        window.location.href = '/checkout/' + data.data.id + '/complete';
    }else{
      fireMessage('Грешка при создавање на нарачката', false, false)
    }

}
</script>

<template>
    <Head>
        <title>Наплата - Хедра Фарм</title>
        <meta name="description" content="Завршете ја вашата нарачка на Хедра Фарм. Безбедно плаќање и брза достава." />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <Header />

    <main>
        <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Наплата</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Наплата</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop checkout -->
        <div class="shop-checkout py-5 bg-white">
            <div class="container">
                <div class="shop-checkout-wrap">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="shop-checkout-step">
                                <div class="accordion" id="shopCheckout">
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#checkoutStep1" aria-expanded="true" aria-controls="checkoutStep1">
                                            Нов Корисник
                                        </button>
                                      </h2>
                                      <div id="checkoutStep1" class="accordion-collapse  show" data-bs-parent="#shopCheckout">
                                        <div class="accordion-body">
                                            <div class="shop-checkout-form">
                                                <form action="#">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Име</label>
                                                                <input type="text" v-model="order.firstName" class="form-control" placeholder="Име">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Презиме</label>
                                                                <input type="text" v-model="order.lastName" class="form-control" placeholder="Презиме">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Еmail</label>
                                                                <input type="email" v-model="order.email" class="form-control" placeholder="Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Телефон</label>
                                                                <input type="text" v-model="order.phone" class="form-control" placeholder="Телефон">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Адреса</label>
                                                                <input type="text" v-model="order.address" class="form-control" placeholder="Адреса">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Град</label>
                                                                <input type="text" v-model="order.city" class="form-control" placeholder="Град">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Општина</label>
                                                                <input type="text" v-model="order.municipality" class="form-control" placeholder="Општина">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Поштенски Код</label>
                                                                <input type="text" v-model="order.postalCode" class="form-control" placeholder="Поштенски код">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Држава</label>
                                                                <input type="text" class="form-control" placeholder="Северна Македонија" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Дополнителен Опис</label>
                                                                <textarea v-model="order.additionalDescription" cols="30" rows="4" class="form-control" placeholder="Your Message"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="shop-cart-summary mt-0">
                                <h5>Вашата Кошничка</h5>
                                <ul>
                                    <li><strong>Подвкупно</strong> <span>{{subtotalBeforeDiscounts.toFixed(2)}} ден</span></li>
                                    <li v-if="totalDiscount > 0"><strong>Попуст:</strong> <span>-{{totalDiscount.toFixed(2)}} ден</span></li>
                                    <li><strong>Вкупно</strong> <span>{{totalPrice.toFixed(2)}} ден</span></li>
                                    <li v-if="totalPrice < 2000"><strong>Достава:</strong> <span>200 ден</span></li>
                                    <li class="shop-cart-total"><strong>Финална цена:</strong> <span>{{(totalPrice + (totalPrice < 2000 ? 200 : 0)).toFixed(2)}} ден</span></li>
                                </ul>
                                <div class="text-end mt-40">
                                    <a @click="createOrder" class="theme-btn">Наплати<i
                                    class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- shop checkout end -->


    </main>
    <Footer />

</template>


<style scoped>

</style>
