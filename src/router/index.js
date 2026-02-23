import { createRouter, createWebHistory } from 'vue-router'
import MainView from '@/views/MainView.vue'
import PageView from '@/views/PageView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: MainView,
      children: [
        {
          path: 'topic/:id',
          name: 'topic',
          component: PageView,
          props: true
        },
        {
      path: '/style',
      name: 'StyleGuide',
      component: () => import('../views/StyleGuide.vue')
    }
      ]
    }
  ]
})

export default router