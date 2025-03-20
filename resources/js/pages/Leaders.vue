<template>
    <div class="p-4">
      <h2 class="text-2xl font-bold">Leaders</h2>
      <p>Check the console for the API response.</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Leaders',
    mounted() {
      axios.get('/api/leaders')
        .then(response => {
          console.log('Leaders:', response.data);
        })
        .catch(error => {
          console.error('Error fetching leaders:', error);
        });
    },
  };
  </script>
  
  <style scoped>
  </style>
  