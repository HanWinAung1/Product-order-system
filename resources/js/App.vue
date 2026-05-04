<template>
  <div id="app">
    <!-- Only show the Nav Bar if the user is logged in -->
    <nav v-if="isLoggedIn" class="p-4 bg-white shadow flex justify-between items-center">
      <router-link to="/dashboard" class="font-bold text-xl text-blue-800">Inventory System</router-link>
      <button @click="handleLogout" class="text-red-600 hover:underline">Logout</button>
    </nav>

    <main class="container mx-auto mt-6">
      <!-- This is where Login.vue or Dashboard.vue will appear -->
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

// Check if user is logged in to show/hide the navigation
const isLoggedIn = computed(() => !!localStorage.getItem('token'));

const handleLogout = () => {
  localStorage.removeItem('token');
  router.push('/login');
};
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}
</style>