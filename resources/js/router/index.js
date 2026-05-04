import { createRouter, createWebHistory } from "vue-router";

const routes = [
  { path: '/', redirect: '/login' },
  { 
    path: '/login', 
    name: 'Login', 
    component: () => import('../views/Login.vue') 
  },
  { 
    path: '/dashboard', 
    name: 'Dashboard', 
    component: () => import('../views/Dashboard.vue'),
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  const token = localStorage.getItem("token");
  if (to.meta.requiresAuth && !token) return { name: "Login" };
  if (to.path === "/login" && token) return { name: "Dashboard" };
});

export default router;