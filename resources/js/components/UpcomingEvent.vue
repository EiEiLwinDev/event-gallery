<template>
    <div class="p-6 bg-gray-100 min-h-screen">
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
      <h1 class="text-3xl font-bold mb-6">Upcoming Event</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="event in events" :key="event.id" class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-700 dark:border-gray-500">
          <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ event.title }}</h5>
          <p class="mb-3 font-normal text-sm text-gray-700 dark:text-gray-400">{{ event.description }}</p>
          <p class="font-normal text-xs text-red-700 dark:text-red-400">End in: {{ formatDateTime(event.date, event.time) }}</p>
          <p class="mb-3 font-normal text-sm text-gray-700 dark:text-gray-400">{{ event.location }}</p>
          <button @click="openUploadModal(event.id)" class="flex flex-row gap-2 items-center text-sm font-small text-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
              Upload photo
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
              </svg>
          </button>
        </div>
        <UploadPhotoModal
          :visible="isUploadModalVisible"
          :onSubmit="handlePhotoUpload"
          :onCancel="closeUploadModal"
        />
      </div>
    </div>
</template>
<script>
import UploadPhotoModal from './modals/UploadPhotoModal.vue';
export default {
    name: 'UpcomingEvent',
    components:{
      UploadPhotoModal
    },
    data() {
      return {
        isUploadModalVisible: false,
        eventIdForUpload: null,
        successMessage: null,
        events: []
      };
    },
    created() {
        this.fetchEvents();
    },
    methods: {
        async fetchEvents() {
          const token = localStorage.getItem('token');
          try {
              const response = await fetch('/api/upcoming-events', {
                  headers: {
                      'Authorization': `Bearer ${token}`,
                      'Content-Type': 'application/json'
                  }
              });
              if (response.status === 401) {
                  window.location.href = '/login';
              } else if (!response.ok) {
                  throw new Error('Network response was not ok');
              }
              const data = await response.json();
              this.events = data;
          } catch (error) {
              console.error('Error fetching events:', error);
          }
      },
      formatDateTime(dateString, timeString) {
          const date = new Date(`${dateString}T${timeString}`);
          return date.toLocaleString();
      }, 
      openUploadModal(eventId) {
        this.eventIdForUpload = eventId;
        this.isUploadModalVisible = true;
      },
      closeUploadModal() {
        this.isUploadModalVisible = false;
        this.eventIdForUpload = null;
      },

      async handlePhotoUpload(file) {
        if (!this.eventIdForUpload) return;

        const formData = new FormData();
        formData.append('photo', file);
        formData.append('event_id', this.eventIdForUpload);

        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/photos', {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`
            },
            body: formData
          });
          this.successMessage = 'Photo uploaded successfully!';
          setTimeout(() => {
            this.successMessage = null;
            this.cancel();
          }, 3000);
          if (!response.ok) throw new Error('Network response was not ok');
          this.closeUploadModal();
          this.fetchEvents();
          
        } catch (error) {
          console.error('Error uploading photo:', error);
        }
      },
    }
};
</script>