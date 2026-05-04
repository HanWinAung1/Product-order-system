<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="p-8 bg-white shadow-lg rounded-lg w-96">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <form @submit.prevent="handleLogin">
        <input v-model="form.email" type="email" placeholder="Email" class="w-full p-2 mb-4 border rounded" required />
        <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 mb-6 border rounded" required />
        <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const loading = ref(false);
const form = reactive({ email: 'admin@example.com', password: 'password' });

const handleLogin = async () => {
  loading.value = true;
  try {
    const response = await axios.post('/api/login', form);
    localStorage.setItem('token', response.data.access_token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
    router.push('/dashboard');
  } catch (error) {
    // Check your browser console (F12) for the detailed red message
    console.error("Login Error Details:", error.response?.data || error.message);
    alert("Login failed! Ensure you ran the seeder.");
  } finally {
    loading.value = false;
  }
};
</script>