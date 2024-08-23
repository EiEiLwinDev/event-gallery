<template>
  <div v-if="visible" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md space-y-4">
      <!-- error alert -->
      <div v-if="errorMessage" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div>
          <span class="font-medium">{{ errorMessage }}</span> 
        </div>
      </div>
      
      <!-- event title -->
      <h6 class="text-lg font-bold dark:text-white">Enrich the Event Gallery with Your Idea</h6>
      <!-- File Upload Area with Image Preview -->
      <div class="relative flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-full">
          <div v-if="!previewUrl" class="flex flex-col items-center justify-center h-full">
            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
            <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
          </div>
          <input id="dropzone-file" type="file" class="hidden" @change="handleFileChange" ref="fileInput" />
          <div v-if="previewUrl" class="absolute inset-0">
            <img :src="previewUrl" alt="Image Preview" class="w-full h-full object-cover rounded-lg border border-gray-300" />
          </div>
        </label>
      </div>
      <!-- Action Buttons -->
      <div class="flex justify-end space-x-4 mt-4">
        <button 
          @click="submit" 
          class="bg-indigo-600 text-sm font-small text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Upload
        </button>
        <button 
          @click="cancel" 
          class="bg-gray-300 text-sm font-small text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UploadPhotoModal',
  props: {
    visible: {
      type: Boolean,
      required: true
    },
    onSubmit: {
      type: Function,
      required: true
    },
    onCancel: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      file: null,
      previewUrl: null,
      errorMessage: null
    };
  },
  methods: {
    handleFileChange(event) {
      const file = event.target.files[0];
      this.file = file;

      if (file) {
        this.previewUrl = URL.createObjectURL(file);
      } else {
        this.previewUrl = null;
      }
    },
    removeImage() {
      this.file = null;
      this.previewUrl = null;
      this.$refs.fileInput.value = '';  
    },
    async submit() {
      if (this.file) {
        try {
          await this.onSubmit(this.file);
          this.previewUrl = null;
        } catch (error) {
          this.successMessage = null;
          this.errorMessage = 'Error uploading photo. Please try again.';
        }
      } else {
        this.successMessage = null;
        this.errorMessage = 'There is no selected photo';
      }
    },
    cancel() {
      this.file = null;
      this.previewUrl = null;
      this.$refs.fileInput.value = '';  
      this.onCancel();
    }
  },
  beforeDestroy() {
    if (this.previewUrl) {
      URL.revokeObjectURL(this.previewUrl);
    }
  }
}
</script>
