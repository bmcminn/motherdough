import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [

    {
      path: window.AppConfig.routes.home,
      name: 'home',
      component: HomeView,
    },


    {
      path: window.AppConfig.routes.about,
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('@/views/AboutView.vue'),
    },


    {
      path: window.AppConfig.routes.login,
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
    },

  ]
})

export default router
