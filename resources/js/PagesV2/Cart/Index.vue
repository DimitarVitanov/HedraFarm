<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from '@/utils/useCart';
import { finalPrice, formatPrice, productUrl, shippingFor, toast, FREE_SHIPPING_FROM } from '@/utils/useShop';

const { cart, removeFromCart, updateQuantity, totalPrice, applyCoupon, removeCoupon } = useCart()
const couponCode = ref('')

const applyCartCoupon = () => {
  if (!couponCode.value.trim()) {
    toast('Внесете код за попуст')
    return
  }

  if (applyCoupon(couponCode.value)) {
    toast('Кодот за попуст е успешно применет!', true)
    couponCode.value = ''
  } else {
    toast('Невалиден код за попуст')
  }
}

// Calculate the total discount amount (product discounts + coupon discount)
const totalDiscount = computed(() => {
  const productDiscountAmount = cart.items.reduce((sum, item) => {
    if (item.disscount) {
      return sum + (item.price * item.disscount / 100) * item.quantity
    }
    return sum
  }, 0)

  return productDiscountAmount + cart.discount
})

// Calculate the subtotal before any discounts
const subtotalBeforeDiscounts = computed(() => {
  return cart.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
})

const shipping = computed(() => shippingFor(totalPrice.value))
</script>

<template>
    <Head>
        <title>Кошничка - Хедра Фарм</title>
        <meta name="description" content="Прегледајте ја вашата кошничка и завршете ја нарачката на Хедра Фарм. Бесплатна достава над 2000 денари." />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <StoreLayout>
        <PageHero title="Вашата Кошничка">
            <div class="hf-steps">
                <span class="is-on"><b>1</b>Кошничка</span><i></i>
                <span><b>2</b>Податоци за достава</span><i></i>
                <span><b>3</b>Потврда</span>
            </div>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container">
                <div v-if="cart.items.length === 0" class="hf-panel hf-empty">
                    <div class="hf-empty__icon"><Icon name="bag" /></div>
                    <h3>Вашата кошничка е празна</h3>
                    <p>Сè уште немате додадено производи. Разгледајте ја нашата понуда.</p>
                    <a href="/store" class="hf-btn">Онлајн Продавница <Icon name="arrow-right" /></a>
                </div>

                <div v-else class="hf-checkout">
                    <div>
                        <div class="hf-panel hf-lines">
                            <div v-for="item in cart.items" :key="item.id" class="hf-line">
                                <a :href="productUrl(item)">
                                    <img class="hf-line__img" :src="item.img ?? item.main_image" :alt="item.title ?? item.name" />
                                </a>
                                <div class="hf-line__info">
                                    <a class="hf-line__name" :href="productUrl(item)">{{ item.title ?? item.name }}</a>
                                    <p class="hf-line__unit">
                                        <del v-if="item.disscount">{{ formatPrice(item.price) }}</del>
                                        {{ formatPrice(finalPrice(item)) }} / парче
                                    </p>
                                </div>
                                <div class="hf-qty">
                                    <button type="button" aria-label="Намали" :disabled="item.quantity <= 1" @click="updateQuantity(item.id, item.quantity - 1)">
                                        <Icon name="minus" />
                                    </button>
                                    <span>{{ item.quantity }}</span>
                                    <button type="button" aria-label="Зголеми" @click="updateQuantity(item.id, item.quantity + 1)">
                                        <Icon name="plus" />
                                    </button>
                                </div>
                                <span class="hf-line__total">{{ formatPrice(finalPrice(item) * item.quantity) }}</span>
                                <button type="button" class="hf-mini__remove" title="Отстрани" @click="removeFromCart(item.id)">
                                    <Icon name="trash" />
                                </button>
                            </div>
                        </div>

                        <div class="hf-coupon">
                            <form @submit.prevent="applyCartCoupon">
                                <div class="hf-inputicon" style="flex: 1">
                                    <Icon name="tag" />
                                    <input v-model="couponCode" type="text" class="hf-input" placeholder="Код за попуст" aria-label="Код за попуст" />
                                </div>
                                <button class="hf-btn hf-btn--dark" type="submit">Примени</button>
                            </form>
                            <a href="/store" class="hf-btn hf-btn--outline"><Icon name="arrow-left" /> Продолжи да купуваш</a>
                            <p v-if="cart.couponCode" class="hf-coupon__ok">
                                Применет купон: {{ cart.couponCode }} (-{{ formatPrice(cart.discount) }})
                                <button type="button" class="hf-link" style="border: 0; background: none; margin-left: 0.5rem" @click="removeCoupon">Отстрани</button>
                            </p>
                        </div>
                    </div>

                    <aside class="hf-panel hf-summary">
                        <h3 class="hf-panel__title">Преглед на нарачка</h3>
                        <div v-if="shipping > 0" class="hf-ship">
                            Уште <strong>{{ formatPrice(FREE_SHIPPING_FROM - totalPrice) }}</strong> до бесплатна достава
                            <div class="hf-ship__bar"><i :style="{ width: (totalPrice / FREE_SHIPPING_FROM) * 100 + '%' }"></i></div>
                        </div>
                        <ul class="hf-summary__rows">
                            <li><span>Подвкупно</span> <span>{{ formatPrice(subtotalBeforeDiscounts) }}</span></li>
                            <li v-if="totalDiscount > 0" class="is-discount"><span>Попуст</span> <span>-{{ formatPrice(totalDiscount) }}</span></li>
                            <li><span>Вкупно</span> <span>{{ formatPrice(totalPrice) }}</span></li>
                            <li :class="{ 'is-free': shipping === 0 }">
                                <span>Достава</span> <span>{{ shipping > 0 ? formatPrice(shipping) : 'Бесплатна' }}</span>
                            </li>
                        </ul>
                        <div class="hf-summary__total">
                            <span>Финална цена</span>
                            <strong>{{ formatPrice(totalPrice + shipping) }}</strong>
                        </div>
                        <a href="/checkout" class="hf-btn hf-btn--lg hf-btn--block">Продолжи кон наплата <Icon name="arrow-right" /></a>
                        <p class="hf-summary__note"><Icon name="shield" /> Плаќање при достава</p>
                    </aside>
                </div>
            </div>
        </section>
    </StoreLayout>
</template>
