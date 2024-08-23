<template>
    <div>
      <header>
        <nav-bar></nav-bar>
      </header>
      <main>
        <router-view></router-view>
      </main>
    </div>
  </template>
  
  <script>
  import { useRouter } from 'vue-router';
  
  export default {
    name: 'Auth',
    setup() {
      const router = useRouter();
      const logout = () => {
        fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
          .then(() => router.push('/login'));
      };
      return { logout };
    }
  };
  </script>
  
   
  