<template>
    <div class="w-full max-w-md mx-auto p-6 bg-white shadow-md rounded-md mt-4">
      <!-- error alert -->
      <div v-if="message" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
              <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
          </svg>
          <span class="sr-only">Info</span>
          <div>
              <span class="font-medium">{{ message }}</span>
          </div>
      </div>
      <h2 class="text-2xl font-bold mb-4">Login</h2>
      <form @submit.prevent="login" class="space-y-4">
          <div class="form-group">
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input v-model="form.email" type="email" id="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
              <span v-if="errors.email">{{ errors.email[0] }}</span>
          </div>
          <div class="form-group">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <input v-model="form.password" type="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
              <span v-if="errors.password">{{ errors.password[0] }}</span>
          </div>
          <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Login</button>
      </form>
      <p class="mt-10 text-center text-sm text-gray-500">
          Not a member?
          {{ ' ' }}
          <RouterLink to="/sign-up" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Sign up</RouterLink>  
      </p>
    </div>
  </template>
  <script>
    import { ref } from 'vue';
    import { RouterLink, useRouter } from 'vue-router';
  
    export default {
        name: 'LoginForm',
        setup() {
        const form = ref({
            email: '',
            password: ''
        });
    
        const errors = ref({});
        const message = ref('');
        const router = useRouter();
    
        const login = async () => {
            try {
              const response = await fetch('/api/login', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(form.value)
              });
              const data = await response.json();
              if (response.ok) { 
                  message.value = data.message || 'Login successful';
                  localStorage.setItem('token', data.token);
                  console.log('login role', data.user.role)
                  localStorage.setItem('role', data.user.role);
                  router.push('/home');
              } else { 
                  errors.value = data.error || 'An unknown error occurred';
                  message.value = errors.value;
              }
            } catch (error) {
              console.error('Login failed:', error);
              message.value = 'Login failed due to a network error';
            }
        };
        return { form, errors, message, login };
      }
    };
  </script>
   
  