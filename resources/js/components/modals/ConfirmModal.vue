<template>
    <div v-if="visible" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
        <p class="mb-4">Are you sure you want to delete this event?</p>
        <div class="flex justify-end space-x-2">
          <button 
            @click="confirmAction" 
            class="bg-red-500 text-white text-xs font-small px-4 py-2 rounded-lg hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
            Yes, Delete
          </button>
          <button 
            @click="cancelAction" 
            class="bg-gray-300 text-xs font-small text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ConfirmModal',
    props: {
      visible: {
        type: Boolean,
        required: true
      },
      onConfirm: {
        type: Function,
        required: true
      },
      onCancel: {
        type: Function,
        required: true
      }
    },
    methods: {
      confirmAction() {
        this.onConfirm();
      },
      cancelAction() {
        this.onCancel();
      }
    }
  }
</script>
