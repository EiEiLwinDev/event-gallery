<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, RouterLink, useRouter } from 'vue-router';

const isAdmin = ref(false);
const userName = ref('');
const router = useRouter();

const route = useRoute();

const fetchUserRole = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await fetch('/api/user', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });
    if (!response.ok) throw new Error('Network response was not ok');
    const user = await response.json();
    isAdmin.value = user.role === 'admin' ? true : false;
    userName.value = user.name;
  } catch (error) {
    console.error('Error fetching user role:', error);
  }
};

const logout = () => {
  localStorage.removeItem('token');
  router.push('/');
};

onMounted(() => {
  fetchUserRole();
});

const getLinkClass = (path) => {
  const isActive = route.path === path;
  return `block py-2 px-3 rounded ${isActive ? 'text-white bg-indigo-700 md:bg-transparent md:text-indigo-700' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white hover:bg-white-100'}`;
};
</script>

<template>
  <nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <div class="hidden w-full md:block md:w-auto" id="navbar-default">
        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <RouterLink :to="{ path: '/home' }" :class="getLinkClass('/home')">Home</RouterLink>
          </li>
          <li>
            <RouterLink :to="{ path: '/upcoming' }" :class="getLinkClass('/upcoming')">Upcoming Event</RouterLink>
          </li>
          <li v-if="isAdmin">
            <RouterLink :to="{ path: '/event' }" :class="getLinkClass('/event')">Event Management</RouterLink>
          </li>
          <li>
            <RouterLink :to="{ path: '/gallery' }" :class="getLinkClass('/gallery')">Gallery</RouterLink>
          </li>
        </ul>
      </div>
      <div class="flex items-center space-x-4">
        <span class="hidden md:block text-gray-900 dark:text-white">Hello {{ userName }}!</span> 
        <button @click="logout" class="text-indigo-700 dark:text-indigo-500 hover:underline">
          Logout
        </button>
      </div>
    </div>
  </nav>
</template>
