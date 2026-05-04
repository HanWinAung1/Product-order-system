<script setup>
import { ref, onMounted, computed } from 'vue'; // Requirement: Axios Integration
import axios from 'axios';

const products = ref([]);
const cart = ref([]);
const loading = ref(false); // Requirement: Loading states

// Requirement: Automatically calculate total_price
const totalPrice = computed(() => {
    return cart.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

// Requirement: Product List GET endpoint
const fetchProducts = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/products');
        products.value = response.data;
    } catch (err) {
        alert("Failed to load products.");
    } finally {
        loading.value = false;
    }
};

const updateQuantity = (productId, amount) => {
    const item = cart.value.find(i => i.id === productId);
    if (item) {
        const newQty = item.quantity + amount;
        const product = products.value.find(p => p.id === productId);
        
        // Requirement: Validation (check stock)[cite: 1]
        if (newQty > 0 && newQty <= product.stock) {
            item.quantity = newQty;
        } else if (newQty > product.stock) {
            alert("Not enough stock!");
        }
    }
};

const addToCart = (product) => {
    const existing = cart.value.find(i => i.id === product.id);
    if (existing) {
        updateQuantity(product.id, 1);
    } else {
        cart.value.push({ ...product, quantity: 1 });
    }
};

// Requirement: Order Creation POST endpoint[cite: 1]
const submitOrder = async () => {
    if (cart.value.length === 0) return;
    
    loading.value = true;
    try {
        const response = await axios.post('/api/orders', {
            items: cart.value.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            }))
        });
        
        alert("Order #" + response.data.order.id + " placed successfully!");
        cart.value = []; 
        await fetchProducts(); // Refresh stock after order
    } catch (error) {
        // Requirement: Return clear error message[cite: 1]
        alert(error.response?.data?.message || "Order failed.");
    } finally {
        loading.value = false;
    }
};

onMounted(fetchProducts);
</script>

<template>
  <div class="p-6">
    <div v-if="loading" class="text-center font-bold text-blue-600 mb-4">Processing...</div>

    <!-- Requirement: Display a list of products[cite: 1] -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="product in products" :key="product.id" class="border p-4 rounded shadow bg-white">
        <h3 class="font-bold text-lg">{{ product.name }}</h3>
        <p class="text-gray-600">${{ product.price }}</p>
        <p class="text-sm text-gray-500">Available Stock: {{ product.stock }}</p>
        <button 
          @click="addToCart(product)" 
          class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
          :disabled="product.stock <= 0"
        >
          {{ product.stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
        </button>
      </div>
    </div>

    <!-- Requirement: Allow users to select items and "Place Order"[cite: 1] -->
    <div v-if="cart.length" class="mt-8 p-6 bg-gray-50 rounded-lg border-2 border-gray-200">
      <h2 class="text-xl font-bold mb-4">Your Order Summary</h2>
      <div v-for="item in cart" :key="item.id" class="flex justify-between items-center border-b py-3">
        <div>
            <span class="font-medium">{{ item.name }}</span>
            <div class="flex items-center space-x-2 mt-1">
                <button @click="updateQuantity(item.id, -1)" class="bg-gray-200 px-2 rounded text-sm">-</button>
                <span class="text-sm font-mono">{{ item.quantity }}</span>
                <button @click="updateQuantity(item.id, 1)" class="bg-gray-200 px-2 rounded text-sm">+</button>
            </div>
        </div>
        <span class="font-semibold">${{ (item.price * item.quantity).toFixed(2) }}</span>
      </div>

      <!-- Requirement: Automatically calculate total_price[cite: 1] -->
      <div class="flex justify-between font-bold text-xl mt-6 pt-4 border-t border-gray-300">
        <span>Grand Total:</span>
        <span class="text-green-700">${{ totalPrice.toFixed(2) }}</span>
      </div>

      <button 
        @click="submitOrder" 
        class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 shadow-md transition"
      >
        Place Order
      </button>
    </div>
  </div>
</template>