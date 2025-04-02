// useCart.js
import { reactive, computed, watch } from 'vue'

const cart = reactive({
  items: JSON.parse(localStorage.getItem('cart')) || []
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
}

const totalPrice = computed(() =>
  cart.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
)

// Persist cart in localStorage
watch(
  () => cart.items,
  (val) => {
    localStorage.setItem('cart', JSON.stringify(val))
  },
  { deep: true }
)

export function useCart() {
  return {
    cart,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart,
    totalPrice,
  }
}
