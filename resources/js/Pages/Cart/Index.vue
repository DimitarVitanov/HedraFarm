<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { useCart } from '@/utils/useCart';

const { cart, removeFromCart, updateQuantity, totalPrice, subtotal, applyCoupon } = useCart()
const couponCode = ref('')

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

const applyCartCoupon = () => {
  if (!couponCode.value.trim()) {
    fireMessage('Внесете код за попуст', false, false)
    return
  }

  if (applyCoupon(couponCode.value)) {
    fireMessage('Кодот за попуст е успешно применет!', true, false)
  } else {
    fireMessage('Невалиден код за попуст', false, false)
  }
}

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

</script>


<template>

    <Header />

    <main>
   <!-- breadcrumb -->
   <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/assets/img/about/about_hero.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Вашата Кошничка</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="/"><i class="fa fa-home"></i> Дома</a></li>
                        <li class="active">Вашата Кошничка</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


             <!-- shop cart -->
             <div class="shop-cart py-90">
                <div class="container">
                    <div class="shop-cart-wrap">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="cart-table">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Слика</th>
                                                    <th>Продукт</th>
                                                    <th>Цена</th>
                                                    <th>Количина</th>
                                                    <th>Вкупно</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="item in cart.items" :key="item.id">
                                                    <td><img :src="item.img ?? item.main_image" alt="Cart Product Image" class="cart-product-image" /></td>
                                                    <td>{{ item.title ?? item.name }}</td>
                                                    <td>
                                                      <span v-if="item.disscount">
                                                        <del>{{ item.price }} ден</del><br>
                                                        {{ (item.price - (item.price * item.disscount / 100)).toFixed(2) }} ден
                                                      </span>
                                                      <span v-else>{{ item.price }} ден</span>
                                                    </td>
                                                    <td>
                                                      <button class="text-danger fs-5 " @click="updateQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1">-</button>
                                                      <input class="cart-input" type="text" :value="item.quantity" disabled />
                                                      <button class="text-success fs-5" @click="updateQuantity(item.id, item.quantity + 1)">+</button>
                                                    </td>
                                                    <td>
                                                      {{ item.disscount ?
                                                         ((item.price - (item.price * item.disscount / 100)) * item.quantity).toFixed(2) :
                                                         (item.quantity * item.price).toFixed(2)
                                                      }} ден
                                                    </td>
                                                    <td><a href="#" @click.prevent="removeFromCart(item.id)"><i class="fa fa-times"></i></a></td>
                                                  </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="shop-cart-footer">
                                    <div class="row">
                                        <div class="col-md-7 col-lg-6">
                                            <div class="shop-cart-coupon">
                                                <form @submit.prevent="applyCartCoupon">
                                                    <div class="form-group">
                                                        <input type="text" v-model="couponCode" class="form-control" placeholder="Доколку имате код за попуст">
                                                        <button class="theme-btn" type="submit">Примени Купон</button>
                                                    </div>
                                                </form>
                                                <div v-if="cart.couponCode" class="text-success mt-2">
                                                    Применет купон: {{ cart.couponCode }} (-{{ cart.discount }} ден)
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-lg-6">
                                            <div class="shop-cart-btn text-md-end">
                                                <a href="/store" class="theme-btn"><span class="fas fa-arrow-left"></span> Продолжи да купуваш</a>
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
                                        <a href="/checkout" class="theme-btn">Наплати<i
                                        class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- shop cart end -->
    </main>


    <Footer/>



</template>

<style scoped>
.cart-product-image{
    width: 100px;
    height: 100px;
}
.cart-input{
    width: 40px;
    margin:10px 10px;
}

</style>
