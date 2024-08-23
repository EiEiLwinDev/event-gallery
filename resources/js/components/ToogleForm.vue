<template>
    <div class="w-full max-w-md mx-auto p-6 bg-white shadow-md rounded-md">
      <div class="flex flex-row justify-center mx-2 mb-4">
        <!-- Login Button -->
        <button 
          @click="currentForm = 'login'" 
          :class="['menu-button', { 'active': currentForm === 'login' }]"
        >
          Login
        </button>
        <!-- Register Button -->
        <button 
          @click="currentForm = 'register'" 
          :class="['menu-button', { 'active': currentForm === 'register' }]"
        >
          Sign Up
        </button>
      </div>
  
      <!-- Register Form -->
      <register-form v-if="currentForm === 'register'" class="mt-8"></register-form>
      
      <!-- Login Form -->
      <login-form v-if="currentForm === 'login'" class="mt-8"></login-form>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import RegisterForm from './RegisterForm.vue'; 
  import LoginForm from './LoginForm.vue';
  
  export default {
    name: 'ToggleForms',
    components: {
      RegisterForm,
      LoginForm
    },
    setup() {
      const currentForm = ref('login');
  
      return { currentForm };
    }
  };
  </script>
  
  <style scoped>
  /* Basic Styles for Menu Buttons */
  .menu-button {
    background: transparent;
    border: none;
    color: #4a5568;
    font-size: 1rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
  }
  
  /* Styles for Active Menu Button */
  .menu-button.active {
    color: #ffffff;
    background-color: #4f46e5;
    border-radius: 0.375rem;
  }
  
  /* Styles for Inactive Menu Button */
  .menu-button:not(.active) {
    background-color: #e5e7eb;
    border-radius: 0.375rem;
  }
  
  /* Hover and Focus States */
  .menu-button:hover,
  .menu-button:focus {
    background-color: #cbd5e1;  
    color: #1a202c;  
  }
  </style>