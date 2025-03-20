<template>
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left section: Brand and desktop navigation -->
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <router-link to="/" class="text-xl font-bold text-gray-800">InvoCivilizations</router-link>
            </div>
            <!-- Desktop Menu: Visible on screens sm and above -->
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link
                to="/"
                :class="getLinkClasses('/')"
              >
                Home
              </router-link>
              <router-link
                to="/civilizations"
                :class="getLinkClasses('/civilizations')"
              >
                Civilizations
              </router-link>
              <router-link
                to="/leaders"
                :class="getLinkClasses('/leaders')"
              >
                Leaders
              </router-link>
            </div>
          </div>
          <!-- Mobile Menu Button: Visible on mobile screens -->
          <div class="-mr-2 flex items-center sm:hidden">
            <button
              @click="isOpen = !isOpen"
              type="button"
              class="bg-gray-100 inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
              aria-controls="mobile-menu"
              aria-expanded="false"
            >
              <span class="sr-only">Toggle navigation</span>
              <svg
                v-if="!isOpen"
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg
                v-else
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
  
      <!-- Mobile Navigation Menu -->
      <div v-if="isOpen" class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
          <router-link
            to="/"
            :class="getMobileLinkClasses('/')"
          >
            Home
          </router-link>
          <router-link
            to="/civilizations"
            :class="getMobileLinkClasses('/civilizations')"
          >
            Civilizations
          </router-link>
          <router-link
            to="/leaders"
            :class="getMobileLinkClasses('/leaders')"
          >
            Leaders
          </router-link>
        </div>
      </div>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Navigation',
    data() {
      return {
        isOpen: false,
      };
    },
    methods: {
      // For desktop links: check if the current route matches the given path.
      getLinkClasses(path) {
        const baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium';
        const activeClasses = 'text-gray-900 border-indigo-500';
        const inactiveClasses = 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300';
        return this.$route.path === path
            ? `${baseClasses} ${activeClasses}`
            : `${baseClasses} ${inactiveClasses}`;
        },
      // For mobile links
      getMobileLinkClasses(path) {
        const baseClasses = 'block pl-3 pr-4 py-2 text-base font-medium';
        const activeClasses = 'border-l-4 border-indigo-500 bg-indigo-50 text-indigo-700';
        const inactiveClasses = 'border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800';
        return this.$route.path === path
            ? `${baseClasses} ${activeClasses}`
            : `${baseClasses} ${inactiveClasses}`;
        },
    },
  };
  </script>
  
  <style scoped>
  </style>
  