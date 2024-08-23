import { createRouter, createWebHistory } from 'vue-router'; 
import HomePage from '../components/HomePage.vue';
import EventGallery from '../components/EventGallery.vue';
import LoginForm from '../components/LoginForm.vue';
import RegisterForm from '../components/RegisterForm.vue';
import Event from '../components/Event.vue';
import Guest from '../components/layouts/Guest.vue';
import Auth from '../components/layouts/Auth.vue';
import UpcomingEvent from '../components/UpcomingEvent.vue';
import { isAuthenticated } from '../auth';

const routes = [
  {
    path: '/',
    component: Guest,
    children: [
      { path: '', component: LoginForm },
      { path: '/sign-up', component: RegisterForm },
    ]
  },
  {
    path: '/',
    component: Auth,
    children: [
      { path: '/home', component: HomePage },
      { path: '/event', component: Event, meta: { requiresAuth: true, requiresAdmin: true } },
      { path: '/upcoming', component: UpcomingEvent },
      { path: '/gallery', component: EventGallery },
    ],
    meta: { requiresAuth: true }
  },
  {
    path: '/:catchAll(.*)',
    redirect: '/'
  }
];
const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin);

  if (requiresAuth) {
    const isValid = isAuthenticated();
    console.log(localStorage.getItem('role'))
    if (!isValid) {
      next('/');
    } else if(requiresAdmin && localStorage.getItem('role') != 'admin'){
      next('/home');
    }else{
      next();
    }
  } else {
    next();
  }
}); 

export default router;
