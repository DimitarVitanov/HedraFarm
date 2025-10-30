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
  // Values represent the discount amount to subtract from total
  const coupons = {
    'SAVE20': 20,    // 20 denars discount
    'SAVE50': 50,    // 50 denars discount  
    'SAVE100': 100,  // 100 denars discount
    'DISCOUNT10': 10, // 10 denars discount
    'НОВГОДИНА': 30   // 30 denars discount
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

const subtotal = computed(() => {
  return cart.items.reduce((sum, item) => {
    // Calculate the price considering individual product discounts
    const itemPrice = item.disscount ? 
      item.price - (item.price * item.disscount / 100) : 
      item.price
    return sum + itemPrice * item.quantity
  }, 0)
})

const totalPrice = computed(() => {
  // Subtract coupon discount from subtotal, but never go below 0
  return Math.max(0, subtotal.value - cart.discount)
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
