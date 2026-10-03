// useShop.js - shared storefront data (fetched once per page load) and helpers
import { ref } from 'vue'

export const FREE_SHIPPING_FROM = 2000
export const SHIPPING_PRICE = 200

const company = ref({})
const categories = ref([])
const products = ref([])
const pending = {}

async function getJson(url) {
  try {
    const response = await fetch(url)
    if (!response.ok) {
      throw new Error('An error occurred while fetching the data')
    }
    const data = await response.json()
    return data.success ? data.data : null
  } catch (error) {
    console.log(error)
    return null
  }
}

const loadOnce = (key, url, target) => {
  pending[key] ??= getJson(url).then((data) => {
    if (data) target.value = data
    return target.value
  })
  return pending[key]
}

const loadCompany = () => loadOnce('company', '/company/fetch', company)
const loadCategories = () => loadOnce('categories', '/product-categories/fetch', categories)
const loadProducts = () => loadOnce('products', '/products/fetch', products)

// Icons for categories the backend map falls back on
const categoryIcons = {
  gels_creams: 'fa-pump-soap',
  children_remedies: 'fa-child',
}
export const categoryIcon = (category) => categoryIcons[category.name] || category.icon || 'fa-box-open'

export const finalPrice = (product) =>
  product.disscount ? product.price - (product.price * product.disscount) / 100 : Number(product.price)

export const formatPrice = (value) => {
  const amount = Math.round((Number(value) || 0) * 100) / 100
  return `${amount.toLocaleString('mk-MK', { maximumFractionDigits: 2 })} ден`
}

export const shippingFor = (total) => (total < FREE_SHIPPING_FROM ? SHIPPING_PRICE : 0)

export const productUrl = (product) => `/products/${product.id}/view`

export const toast = (message, success = false, reload = false) => {
  Swal.fire({
    position: 'top',
    toast: true,
    icon: success ? 'success' : 'error',
    title: message,
    showConfirmButton: false,
    timer: 1500,
  }).then(() => {
    if (reload) {
      location.reload()
    }
  })
}

export function useShop() {
  return {
    company,
    categories,
    products,
    loadCompany,
    loadCategories,
    loadProducts,
  }
}
