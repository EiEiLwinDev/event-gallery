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
    <h1 class="text-3xl font-bold mb-6">Manage Events</h1>
    <button @click="showAddEventModal = true" class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-indigo-700 mb-4">Add New Event</button>
    <!-- event table -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Time
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descritption
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Location
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="event in events" :key="event.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      {{ event.title }}
                    </th>
                    <td class="px-6 py-4">
                      {{ formatDate(event.date) }}
                    </td>
                    <td class="px-6 py-4">
                      {{ formatTime(event.time) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ event.description }}
                    </td>
                    <td class="px-6 py-4">
                        {{ event.location }}
                    </td>
                    <td class="flex flex-row gap-2 items-center px-6 py-4 text-right">
                        <button @click="editEvent(event)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</button>
                        <button @click="confirmDeleteEvent(event.id)" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- create form -->
    <div v-if="showAddEventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Add New Event</h2>
        <form @submit.prevent="addEvent">
          <div class="flex flex-row gap-2 justify-start items-center">
            <div class="mb-4">
              <label for="date" class="block text-gray-700">Date</label>
              <input v-model="newEvent.date" type="date" id="date" class="mt-1 p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
              <label for="time" class="block text-gray-700">Time</label>
              <input v-model="newEvent.time" type="time" id="time" class="mt-1 p-2 border rounded-lg" required>
            </div>
          </div>
          <div class="mb-4">
            <label for="title" class="block text-gray-700">Title</label>
            <input v-model="newEvent.title" type="text" id="title" class="mt-1 p-2 border rounded-lg w-full" required>
          </div>
          <div class="mb-4">
            <label for="location" class="block text-gray-700">Location</label>
            <input v-model="newEvent.location" id="location" class="mt-1 p-2 border rounded-lg w-full" required />
          </div>
          <div class="mb-4">
            <label for="description" class="block text-gray-700">Description</label>
            <textarea v-model="newEvent.description" id="description" class="mt-1 p-2 border rounded-lg w-full" rows="5"></textarea>
          </div>
          <button type="submit" class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-indigo-700 mb-4">Add Event</button>
          <button @click="cancelAddEventModal" type="button" class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-red-700 mb-4 mx-2">Cancel</button>
        </form>
      </div>  
    </div>
    <!-- Edit Form -->
    <div v-if="showEditEventModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Edit Event</h2>
        <form @submit.prevent="updateEvent">
          <div class="flex flex-row gap-2 justify-start items-center">
            <div class="mb-4">
              <label for="edit-date" class="block text-gray-700">Date</label>
              <input v-model="currentEvent.date" type="date" id="edit-date" class="mt-1 p-2 border rounded-lg w-full" required>
            </div>
            <div class="mb-4">
              <label for="edit-time" class="block text-gray-700">Time</label>
              <input v-model="currentEvent.time" type="time" id="edit-time" class="mt-1 p-2 border rounded-lg w-full" required>
            </div>
          </div>
          <div class="mb-4">
            <label for="edit-title" class="block text-gray-700">Title</label>
            <input v-model="currentEvent.title" type="text" id="edit-title" class="mt-1 p-2 border rounded-lg w-full" required>
          </div>
          <div class="mb-4">
            <label for="edit-location" class="block text-gray-700">Location</label>
            <input v-model="currentEvent.location" id="edit-location" class="mt-1 p-2 border rounded-lg w-full" rows="3" required />
          </div>
          <div class="mb-4">
            <label for="edit-description" class="block text-gray-700">Description</label>
            <textarea v-model="currentEvent.description" id="edit-description" class="mt-1 p-2 border rounded-lg w-full" rows="5"></textarea>
          </div>
          <button type="submit" class="bg-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-indigo-700 mb-4">Update Event</button>
          <button @click="showEditEventModal = false" type="button" class="bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-red-700 mb-4 mx-2">Cancel</button>
        </form>
      </div>
    </div>
    <!-- confirm modal -->
    <ConfirmModal
      :visible="showConfirmationModal"
      :onConfirm="deleteEvent"
      :onCancel="cancelDelete"
    />
  </div>
</template>
<script>
  import ConfirmModal from './modals/ConfirmModal.vue'; 
  export default {
    name: 'Event',
    components: {
      ConfirmModal
    },
    data() {
      return {
        events: [],
        showAddEventModal: false,
        showEditEventModal: false,
        showConfirmationModal: false,
        eventToDelete: null,
        successMessage: null,
        errorMessage: null,
        newEvent: {
          title: '',
          description: '',
          date: '',
          time: ''
        },
        currentEvent: null, 
      };
    },
    
    created() {
      this.fetchEvents();
    },
    methods: {
      async fetchEvents() {
        const token = localStorage.getItem('token');
        try {
          const response = await fetch('/api/events', {
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
      async addEvent() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch('/api/events', {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.newEvent)
          });
          this.successMessage = 'Event created successfully!';
          if (!response.ok) throw new Error('Network response was not ok');
          this.showAddEventModal = false;
          this.newEvent.date = '';
          this.newEvent.time = '';
          this.newEvent.title = '';
          this.newEvent.description = '';
          this.newEvent.location = '';
          this.fetchEvents();
        } catch (error) {
          this.errorMessage = 'Event created error!';
          console.error('Error adding event:', error);
        }
      },
      async editEvent(event) {
        this.currentEvent = { ...event };
        this.showEditEventModal = true;
      },
      async cancelAddEventModal(event) {
        this.showAddEventModal = false;
        this.newEvent.date = '';
        this.newEvent.time = '';
        this.newEvent.title = '';
        this.newEvent.description = '';
        this.newEvent.location = '';
      },
      async updateEvent() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/events/${this.currentEvent.id}`, {
            method: 'PATCH',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.currentEvent)
          });
          this.successMessage = 'Event updated successfully!';
          if (!response.ok) throw new Error('Network response was not ok');
          this.showEditEventModal = false;
          this.fetchEvents();
        } catch (error) {
          this.errorMessage = 'Event updated error!';
          console.error('Error updating event:', error);
        }
      },
      confirmDeleteEvent(eventId) {
        this.eventToDelete = eventId;
        this.showConfirmationModal = true;
      },
      async deleteEvent() {
        try {
          const token = localStorage.getItem('token');
          const response = await fetch(`/api/events/${this.eventToDelete}`, {
            method: 'DELETE',
            headers: {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
            }
          });
          this.successMessage = 'Event deleted successfully!';
          if (!response.ok) throw new Error('Network response was not ok');
          this.showConfirmationModal = false; 
          this.fetchEvents();
        } catch (error) {
          this.errorMessage = 'Event deleted error!';
          console.error('Error deleting event:', error);
        }
      },
      cancelDelete() {
        this.showConfirmationModal = false;
        this.eventToDelete = null;
      },
      formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
      },
      formatTime(time) {
        if (!time) return '';
        const [hours, minutes] = time.split(':');
        let hour = parseInt(hours, 10);
        const period = hour >= 12 ? 'PM' : 'AM';
        hour = hour % 12 || 12;
        return `${hour}:${minutes.padStart(2, '0')}${period}`;
      }

    }
  };
</script>
     