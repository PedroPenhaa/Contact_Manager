<template>
  <div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
    <div class="fixed inset-0 transform transition-all">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div class="transform transition-all sm:w-full sm:max-w-md sm:mx-auto">
      <div
        class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
        @click.stop
      >
        <div class="absolute top-0 right-0 pt-4 pr-4">
          <button
            type="button"
            class="text-gray-400 hover:text-gray-500"
            @click="$emit('close')"
          >
            <span class="sr-only">Close</span>
            <svg
              class="h-6 w-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
defineEmits(['close'])
</script> 