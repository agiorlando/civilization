<template>
    <div class="p-4">
      <h2 class="text-2xl font-bold">Civilizations</h2>
      <p>Check the console for the API response.</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Civilizations',
    mounted() {
      axios.get('/api/civilizations')
        .then(response => {
          console.log('Civilizations:', response.data);
        })
        .catch(error => {
          console.error('Error fetching civilizations:', error);
        });
    },
  };
  </script>
  
  <style scoped>
  </style>
  