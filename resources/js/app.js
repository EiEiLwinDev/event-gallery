import { createApp, ref } from 'vue';
import '../css/app.css';
import router from './router';
import RegisterForm from './components/RegisterForm.vue';
import LoginForm from './components/LoginForm.vue';
import EventGallery from './components/EventGallery.vue';
import ToogleForm from './components/ToogleForm.vue';
import HomePage from './components/HomePage.vue';
import Nav from './components/Nav.vue';
import Event from './components/Event.vue';
import Guest from './components/layouts/Guest.vue';
import Auth from './components/layouts/Auth.vue'; 
import UpcomingEvent from './components/UpcomingEvent.vue';

const app = createApp({});

app.component('toogle-form', ToogleForm)
app.component('login-form', LoginForm)
app.component('register-form', RegisterForm)
app.component('nav-bar', Nav)
app.component('home-page', HomePage)
app.component('gallery', EventGallery)
app.component('event', Event)
app.component('upcoming-event', UpcomingEvent)
app.component('guest-layout', Guest)
app.component('auth-layout', Auth) 
app.use(router);
app.mount('#app');