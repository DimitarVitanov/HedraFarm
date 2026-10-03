<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, nextTick } from 'vue';
import StoreLayout from '@/Layouts/V2/StoreLayout.vue';
import PageHero from '@/Components/V2/PageHero.vue';
import Icon from '@/Components/V2/Icon.vue';
import { useCart } from '@/utils/useCart';
import { finalPrice, formatPrice, shippingFor, toast } from '@/utils/useShop';

const { cart, totalPrice, clearCart } = useCart()
const submitting = ref(false)

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
    if (submitting.value) return

    if(order.value.firstName === '' || order.value.lastName === '' || order.value.email === '' || order.value.phone === '' || order.value.address === '' || order.value.city === '' || order.value.postalCode === ''){
        toast('Пополнете ги сите полиња')
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
    formData.append('deliveryPrice', shipping.value);
    formData.append('discount', totalDiscount.value);
    formData.append('totalPrice', totalPrice.value + shipping.value);
    formData.append('paymentMethod', 'cash');
    formData.append('status', 'pending');

    const _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', _token);

    submitting.value = true
    try {
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
            clearCart()
            await nextTick()
            window.location.href = '/checkout/' + data.data.id + '/complete';
            return
        }
        toast('Грешка при создавање на нарачката')
    } catch (error) {
        console.log(error)
        toast('Грешка при создавање на нарачката')
    }
    submitting.value = false
}
</script>

<template>
    <Head>
        <title>Наплата - Хедра Фарм</title>
        <meta name="description" content="Завршете ја вашата нарачка на Хедра Фарм. Безбедно плаќање и брза достава." />
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <StoreLayout>
        <PageHero title="Наплата" :trail="[{ label: 'Кошничка', href: '/cart-preview' }]">
            <div class="hf-steps">
                <span class="is-on"><b><Icon name="check" /></b>Кошничка</span><i></i>
                <span class="is-on"><b>2</b>Податоци за достава</span><i></i>
                <span><b>3</b>Потврда</span>
            </div>
        </PageHero>

        <section class="hf-section">
            <div class="hf-container">
                <div v-if="cart.items.length === 0" class="hf-panel hf-empty">
                    <div class="hf-empty__icon"><Icon name="bag" /></div>
                    <h3>Вашата кошничка е празна</h3>
                    <p>Додадете производи во кошничката пред да ја завршите нарачката.</p>
                    <a href="/store" class="hf-btn">Онлајн Продавница <Icon name="arrow-right" /></a>
                </div>

                <form v-else class="hf-checkout" @submit.prevent="createOrder">
                    <div class="hf-panel">
                        <h3 class="hf-panel__title">Податоци за достава</h3>
                        <div class="hf-form-grid">
                            <div class="hf-field">
                                <label for="co-first">Име</label>
                                <input id="co-first" v-model="order.firstName" type="text" class="hf-input" placeholder="Име" autocomplete="given-name" />
                            </div>
                            <div class="hf-field">
                                <label for="co-last">Презиме</label>
                                <input id="co-last" v-model="order.lastName" type="text" class="hf-input" placeholder="Презиме" autocomplete="family-name" />
                            </div>
                            <div class="hf-field">
                                <label for="co-email">Email</label>
                                <input id="co-email" v-model="order.email" type="email" class="hf-input" placeholder="Email" autocomplete="email" />
                            </div>
                            <div class="hf-field">
                                <label for="co-phone">Телефон</label>
                                <input id="co-phone" v-model="order.phone" type="tel" class="hf-input" placeholder="Телефон" autocomplete="tel" />
                            </div>
                            <div class="hf-field hf-col-2">
                                <label for="co-address">Адреса</label>
                                <input id="co-address" v-model="order.address" type="text" class="hf-input" placeholder="Улица и број" autocomplete="street-address" />
                            </div>
                            <div class="hf-field">
                                <label for="co-city">Град</label>
                                <input id="co-city" v-model="order.city" type="text" class="hf-input" placeholder="Град" autocomplete="address-level2" />
                            </div>
                            <div class="hf-field">
                                <label for="co-municipality">Општина</label>
                                <input id="co-municipality" v-model="order.municipality" type="text" class="hf-input" placeholder="Општина" />
                            </div>
                            <div class="hf-field">
                                <label for="co-postal">Поштенски код</label>
                                <input id="co-postal" v-model="order.postalCode" type="text" class="hf-input" placeholder="Поштенски код" autocomplete="postal-code" />
                            </div>
                            <div class="hf-field">
                                <label for="co-country">Држава</label>
                                <input id="co-country" type="text" class="hf-input" :value="order.country" readonly />
                            </div>
                            <div class="hf-field hf-col-2">
                                <label for="co-note">Дополнителен опис</label>
                                <textarea id="co-note" v-model="order.additionalDescription" rows="4" class="hf-textarea" placeholder="Забелешка за нарачката или доставата (опционално)"></textarea>
                            </div>
                        </div>

                        <h3 class="hf-panel__title" style="margin-top: 2rem">Начин на плаќање</h3>
                        <div class="hf-pay">
                            <Icon name="wallet" />
                            <div>
                                <strong>Плаќање при достава</strong>
                                <small>Платете во готово кога ќе ја примите пратката.</small>
                            </div>
                        </div>
                    </div>

                    <aside class="hf-panel hf-summary">
                        <h3 class="hf-panel__title">Вашата нарачка</h3>
                        <ul class="hf-summary__items" data-lenis-prevent>
                            <li v-for="item in cart.items" :key="item.id">
                                <img :src="item.img ?? item.main_image" :alt="item.title ?? item.name" />
                                <span>{{ item.title ?? item.name }} <small style="color: var(--hf-muted)">× {{ item.quantity }}</small></span>
                                <strong>{{ formatPrice(finalPrice(item) * item.quantity) }}</strong>
                            </li>
                        </ul>
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
                        <button type="submit" class="hf-btn hf-btn--lg hf-btn--block" :disabled="submitting">
                            {{ submitting ? 'Се испраќа...' : 'Потврди нарачка' }} <Icon v-if="!submitting" name="arrow-right" />
                        </button>
                        <p class="hf-summary__note"><Icon name="shield" /> Вашите податоци се безбедни кај нас</p>
                    </aside>
                </form>
            </div>
        </section>
    </StoreLayout>
</template>
