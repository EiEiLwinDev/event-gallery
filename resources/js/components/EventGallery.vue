<template>
  <div class="p-6 space-y-4">
    <!-- success alert -->
    <div v-if="successMessage" class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
      <span class="sr-only">Info</span>
      <div>
        <span class="font-medium">{{ successMessage }}</span>
      </div>
    </div>
    <!-- error alert -->
    <div v-if="errorMessage" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
      </svg>
      <span class="sr-only">Info</span>
      <div>
        <span class="font-medium">{{ errorMessage }}</span>
      </div>
    </div>
    <!-- Page Title -->
    <h1 class="text-2xl font-bold mb-4">Gallery</h1>
    <!-- Photo Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="photo in photos" :key="photo.id" class="bg-white rounded-lg shadow-lg">
        <div class="relative">
          <img :src="`/storage/${photo.photo_url}`" alt="Event Photo" class="w-full h-48 object-cover rounded-md border border-gray-300" />
          <div :class="getStatusClass(photo.status)" class="absolute top-0 left-0 text-white text-xs px-2 py-1 rounded-br-lg bg-opacity-50">
            {{ capitalize(photo.status) }}
          </div>
        </div>
        <div class="flex flex-col justify-between p-4">
          <div>
            <h2 class="text-lg font-semibold">{{ photo.event.title }}</h2>
            <p class="text-red-400 text-xs font-small">{{ formatDateTime(photo.event.date, photo.event.time) }}</p>
            <p class="text-gray-400 text-sm font-small mt-2"> Uploaded by <span class="text-gray-600"> {{ photo.user.name }}</span></p>
          </div>
          <div class="flex space-x-2 mt-2">
            <div v-if="isAdmin">
              <div v-if="photo.status === 'pending'" class="space-x-2">
                <button @click="approvePhoto(photo.id)" type="button" class="px-3 py-2 text-xs font-small text-center inline-flex items-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                  Approve
                </button>
                <button @click="rejectPhoto(photo.id)" type="button" class="px-3 py-2 text-xs font-small text-center inline-flex items-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                  Reject
                </button>
              </div>
            </div>
            <div v-if="photo.status === 'pending'">
              <button @click="deletePhoto(photo.id)" type="button" class="px-3 py-2 text-xs font-small text-center inline-flex items-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      photos: [],
      isAdmin: false,
      successMessage: null,
      errorMessage: null
    };
  },
  created() {
    this.fetchPhotos();
    this.checkUserRole();
  },
  methods: {
    async fetchPhotos() {
      try {
        const token = localStorage.getItem('token');
        const response = await fetch('/api/photos', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();
        this.photos = data;
      } catch (error) {
        console.error('Error fetching photos:', error);
      }
    },
    async checkUserRole() {
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
        this.isAdmin = user.role === 'admin' ? true : false;
      } catch (error) {
        console.error('Error fetching user role:', error);
      }
    },
    async approvePhoto(photoId) {
      try {
        const token = localStorage.getItem('token');
        const response = await fetch(`/api/photos/${photoId}/approve`, {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
        this.successMessage = 'Photo approved successfully!';
        if (!response.ok) throw new Error('Network response was not ok');
        const updatedPhoto = await response.json();
        this.photos = this.photos.map(photo =>
          photo.id === updatedPhoto.id ? updatedPhoto : photo
        );
        this.fetchPhotos()
      } catch (error) {
        this.errorMessage = 'Photo approved error!';
        console.error('Error approving photo:', error);
      }
    },
    async rejectPhoto(photoId) {
      try {
        const token = localStorage.getItem('token');
        const response = await fetch(`/api/photos/${photoId}/reject`, {
          method: 'PATCH',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
        this.successMessage = 'Photo rejected successfully!';
        if (!response.ok) throw new Error('Network response was not ok');
        const updatedPhoto = await response.json();
        this.photos = this.photos.map(photo =>
          photo.id === updatedPhoto.id ? updatedPhoto : photo
        );
        this.fetchPhotos();
      } catch (error) {
        this.errorMessage = 'Photo rejected error!';
        console.error('Error rejecting photo:', error);
      }
    },
    async deletePhoto(photoId) {
      try {
        const token = localStorage.getItem('token');
        const response = await fetch(`/api/photos/${photoId}`, {
          method: 'DELETE',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        });
        this.errorMessage = 'Photo deleted successfully';
        if (!response.ok) throw new Error('Network response was not ok');
        this.fetchPhotos();
      } catch (error) {
        this.errorMessage = 'Photo deleted error!';
        console.error('Error rejecting photo:', error);
      }
    },
    formatDateTime(dateString, timeString) {
      const date = new Date(`${dateString}T${timeString}`);
      return date.toLocaleString();
    }, 
    getStatusClass(status) {
      switch (status) {
        case 'approved':
          return 'bg-green-600';
        case 'rejected':
          return 'bg-red-600';
        default:
          return 'bg-black';
      }
    },
    capitalize(text) {
      return text.charAt(0).toUpperCase() + text.slice(1);
    }
  }
}
</script> 