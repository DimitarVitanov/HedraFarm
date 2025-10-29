// useCart.js
import { reactive, computed, watch } from 'vue'

const cart = reactive({
  items: JSON.parse(localStorage.getItem('cart')) || [],
  discount: 0,
  couponCode: ''
})

const addToCart = (product) => {
  const existing = cart.items.find(item => item.id === product.id)
  if (existing) {
    existing.quantity += 1
  } else {
    cart.items.push({ ...product, quantity: 1 })
  }
}

const removeFromCart = (id) => {
  cart.items = cart.items.filter(item => item.id !== id)
}

const updateQuantity = (id, qty) => {
  const item = cart.items.find(i => i.id === id)
  if (item) item.quantity = qty
}

const clearCart = () => {
  cart.items = []
  cart.discount = 0
  cart.couponCode = ''
}

const applyCoupon = (couponCode) => {
  // Simple coupon logic - you can extend this with API calls
  // Values represent the final discounted price the customer will pay
  const coupons = {
    'SAVE20': 20,    // Customer pays 20 denars regardless of original price
    'SAVE50': 50,    // Customer pays 50 denars regardless of original price
    'SAVE100': 100,  // Customer pays 100 denars regardless of original price
    'DISCOUNT10': 10, // Customer pays 10 denars regardless of original price
    'НОВГОДИНА': 30   // Customer pays 30 denars regardless of original price
  }
  
  if (coupons[couponCode.toUpperCase()]) {
    cart.discount = coupons[couponCode.toUpperCase()]
    cart.couponCode = couponCode.toUpperCase()
    return true
  }
  return false
}

const removeCoupon = () => {
  cart.discount = 0
  cart.couponCode = ''
}

const subtotal = computed(() =>
  cart.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
)

const totalPrice = computed(() => {
  // If discount is applied, the total becomes the discount value (discounted price)
  // Otherwise, use the subtotal
  return cart.discount > 0 ? cart.discount : subtotal.value
})

// Persist cart in localStorage
watch(
  () => cart,
  (val) => {
    localStorage.setItem('cart', JSON.stringify(val.items))
    localStorage.setItem('cartDiscount', JSON.stringify(val.discount))
    localStorage.setItem('cartCoupon', val.couponCode)
  },
  { deep: true }
)

// Initialize cart with stored discount and coupon
const initCart = () => {
  const storedDiscount = localStorage.getItem('cartDiscount')
  const storedCoupon = localStorage.getItem('cartCoupon')
  
  if (storedDiscount) {
    cart.discount = JSON.parse(storedDiscount)
  }
  if (storedCoupon) {
    cart.couponCode = storedCoupon
  }
}

// Initialize on load
initCart()

export function useCart() {
  return {
    cart,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    applyCoupon,
    removeCoupon,
    subtotal,
    totalPrice,
  }
}
