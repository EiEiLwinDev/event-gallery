<template>
  <div class="p-6 space-y-4"> 
    <h1 class="text-3xl font-bold mb-6 text-center">Welcome to Event Gallery!</h1>
    <!-- Events List -->
    <div v-for="(event, index) in events" :key="event.id">
      <!-- Event Header -->
      <div class="my-4">
        <h2 class="text-xl font-semibold">{{ event.title }}</h2>
        <div class="border-t border-gray-300"></div>
        <p class="text-red-600 text-xs font-small mt-2">{{ formatDateTime(event.date, event.time) }}</p>
        <p class="text-gray-500 text-xs font-small flex flex-row items-center">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
            <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.544l.062.029.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd" />
          </svg>
          {{ event.location }}
        </p>
      </div>
      <div v-if="event.photos.length > 0">
        <div class="grid sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
          <div v-for="photo in event.photos" :key="photo.id">
            <img 
              class="w-full h-96 sm:h-auto lg:h-96 md:h-96 object-cover rounded-lg" 
              :src="`/storage/${photo.photo_url}`" 
              alt="Event Photo" 
            />
          </div>
        </div>
      </div>
      <div v-else class="text-center text-gray-500">
        <p>No photos uploaded for this event.</p>
      </div>
      
    </div>
    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const events = ref([]);

const fetchEvents = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await fetch('/api/events-photos', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    });
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    events.value = data;
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

const formatDateTime = (dateString, timeString) => {
  const date = new Date(`${dateString}T${timeString}`);
  return date.toLocaleString();
};

onMounted(() => {
  fetchEvents();
});
</script>
