import { createApp } from 'vue';
import axios from 'axios';
import App from './App.vue';
import router from './router/index.js';

// Automatically match the URL in your browser address bar
axios.defaults.baseURL = window.location.origin; 
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set token if it exists
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

createApp(App).use(router).mount('#app');