<template>
  <div class="mt-4 flex justify-between items-center">
    <button
      @click="$emit('prev')"
      :disabled="currentPage === 1"
      class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded disabled:opacity-50"
    >
      Previous
    </button>
    <div>Page {{ currentPage }} of {{ totalPages }}</div>
    <button
      @click="$emit('next')"
      :disabled="currentPage === totalPages"
      class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded disabled:opacity-50"
    >
      Next
    </button>
  </div>
</template>

<script>
export default {
  name: "PaginationControls",
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    totalPages: {
      type: Number,
      required: true,
    },
  },
};
</script>
