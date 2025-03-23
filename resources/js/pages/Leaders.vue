<template>
  <div class="p-4">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
      <h2 class="text-2xl font-bold mb-2 sm:mb-0">Leaders</h2>
      <div class="flex items-center space-x-4">
        <SearchFilter v-model="searchQuery" placeholder="Search leader, civilization, or subtitle" />
        <button
          @click="openForm()"
          class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer"
        >
          Create New Leader
        </button>
      </div>
    </div>

    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 border-b border-gray-200">ID</th>
          <th class="py-2 border-b border-gray-200">Name</th>
          <th class="py-2 border-b border-gray-200">Subtitle</th>
          <th class="py-2 border-b border-gray-200">Civilization</th>
          <th class="py-2 border-b border-gray-200">Lifespan</th>
          <th class="py-2 border-b border-gray-200">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="leader in paginatedLeaders"
          :key="leader.id"
          class="hover:bg-gray-50"
        >
          <td class="px-4 py-2 border-b border-gray-200">{{ leader.id }}</td>
          <td class="px-4 py-2 border-b border-gray-200">
            <div class="flex items-center">
              <img :src="leader.icon" alt="icon" class="w-8 h-8 mr-2" />
              <span class="text-blue-600 cursor-pointer" @click="viewLeader(leader.id)">
                {{ leader.name }}
              </span>
            </div>
          </td>
          <td class="px-4 py-2 border-b border-gray-200">{{ leader.subtitle }}</td>
          <td class="px-4 py-2 border-b border-gray-200">
            <span
              v-if="leader.civilization"
              class="text-blue-600 cursor-pointer"
              @click="viewCivilization(leader.civilization.id)"
            >
              {{ leader.civilization.name }}
            </span>
            <span v-else class="text-gray-500 text-sm">None</span>
          </td>
          <td class="px-4 py-2 border-b border-gray-200">{{ leader.lifespan }}</td>
          <td class="px-4 py-2 border-b border-gray-200">
            <button
              @click="openForm(leader)"
              class="bg-blue-500 text-white px-2 py-1 rounded mr-2 cursor-pointer"
            >
              Edit
            </button>
            <button
              @click="deleteLeader(leader.id)"
              class="bg-red-500 text-white px-2 py-1 rounded cursor-pointer"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination Controls Component -->
    <PaginationControls
      :currentPage="currentPage"
      :totalPages="totalPages"
      @prev="prevPage"
      @next="nextPage"
    />

    <!-- Modal for Create/Edit Leader Form -->
    <div
      v-if="showForm"
      @click.self="cancelForm"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
    >
      <div class="bg-white p-6 rounded shadow-md w-full max-w-md mx-4 max-h-[80vh] overflow-y-auto relative">
        <button
          @click="cancelForm"
          class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 font-bold text-xl"
        >
          &times;
        </button>
        <h3 class="text-xl font-semibold mb-4">
          {{ editing ? "Edit Leader" : "Create New Leader" }}
        </h3>
        <form @submit.prevent="saveLeader">
          <div class="mb-4">
            <label class="block mb-1">Name:</label>
            <input
              type="text"
              v-model="form.name"
              class="border px-3 py-2 w-full"
              required
            />
            <div v-if="errors.name" class="mt-1 text-red-600 text-sm">
              <div v-for="(msg, index) in errors.name" :key="index">{{ msg }}</div>
            </div>
          </div>
          <!-- Dropdown for selecting an unused civilization -->
          <div class="mb-4">
            <label class="block mb-1">Civilization:</label>
            <select
              v-model="form.civilization_id"
              class="border px-3 py-2 w-full"
              required
            >
              <option value="" disabled>Select a civilization</option>
              <option
                v-for="civ in availableCivilizations"
                :key="civ.id"
                :value="civ.id"
              >
                {{ civ.name }}
              </option>
            </select>
            <div v-if="errors.civilization_id" class="mt-1 text-red-600 text-sm">
              <div v-for="(msg, index) in errors.civilization_id" :key="index">{{ msg }}</div>
            </div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Icon URL:</label>
            <input
              type="text"
              v-model="form.icon"
              class="border px-3 py-2 w-full"
              required
            />
            <div v-if="errors.icon" class="mt-1 text-red-600 text-sm">
              <div v-for="(msg, index) in errors.icon" :key="index">{{ msg }}</div>
            </div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Subtitle:</label>
            <input
              type="text"
              v-model="form.subtitle"
              class="border px-3 py-2 w-full"
            />
            <div v-if="errors.subtitle" class="mt-1 text-red-600 text-sm">
              <div v-for="(msg, index) in errors.subtitle" :key="index">{{ msg }}</div>
            </div>
          </div>
          <div class="mb-4">
            <label class="block mb-1">Lifespan:</label>
            <input
              type="text"
              v-model="form.lifespan"
              class="border px-3 py-2 w-full"
            />
            <div v-if="errors.lifespan" class="mt-1 text-red-600 text-sm">
              <div v-for="(msg, index) in errors.lifespan" :key="index">{{ msg }}</div>
            </div>
          </div>
          <div class="flex justify-end">
            <button
              type="button"
              @click="cancelForm"
              class="mr-2 bg-gray-500 text-white px-4 py-2 rounded cursor-pointer"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer"
            >
              {{ editing ? "Update" : "Create" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal for Viewing Leader Details (Historical Info) -->
    <div
      v-if="leaderModalVisible"
      @click.self="closeLeaderModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
    >
      <div class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto relative">
        <button
          @click="closeLeaderModal"
          class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 font-bold text-xl"
        >
          &times;
        </button>
        <h3 class="text-xl font-bold mb-2">
          <span>{{ leaderDetail.name }}</span>
          <sup class="text-xs text-gray-500 ml-2">{{ leaderDetail.lifespan }}</sup>
        </h3>
        <p class="text-sm text-gray-700 mb-4">{{ leaderDetail.subtitle }}</p>
        <h4 class="font-semibold mb-2">Historical Info:</h4>
        <ul>
          <li
            v-for="(info, index) in leaderDetail.historical_info"
            :key="index"
            class="border-b border-gray-200 py-2"
          >
            <strong>{{ info.heading }}:</strong> {{ info.text }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Modal for Viewing Civilization Details (from Leader page) -->
    <div
      v-if="civModalVisible"
      @click.self="closeCivModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
    >
      <div class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto relative">
        <button
          @click="closeCivModal"
          class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 font-bold text-xl"
        >
          &times;
        </button>
        <h3 class="text-xl font-bold mb-4">{{ civDetail.name }}</h3>
        <img :src="civDetail.icon" alt="icon" class="w-16 h-16 mb-4" />
        <h4 class="font-semibold mb-2">Historical Info:</h4>
        <ul>
          <li
            v-for="(info, index) in civDetail.historical_info"
            :key="index"
            class="border-b border-gray-200 py-2"
          >
            <strong>{{ info.heading }}:</strong> {{ info.text }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import axios from 'axios';
import SearchFilter from '@/components/SearchFilter.vue';
import PaginationControls from '@/components/PaginationControls.vue';

export default defineComponent({
  name: 'Leaders',
  components: {
    SearchFilter,
    PaginationControls,
  },
  data() {
    return {
      leaders: [] as any[],
      availableCivilizations: [] as any[],
      showForm: false,
      editing: false,
      form: {
        name: '',
        civilization_id: '',
        icon: '',
        subtitle: '',
        lifespan: '',
      },
      errors: {} as Record<string, string[]>,
      currentId: null as number | null,
      leaderModalVisible: false,
      leaderDetail: {} as any,
      civModalVisible: false,
      civDetail: {} as any,
      searchQuery: '',
      currentPage: 1,
      pageSize: 10,
    };
  },
  watch: {
    searchQuery() {
      // Reset current page when the search query changes.
      this.currentPage = 1;
    },
  },
  computed: {
    filteredLeaders(): any[] {
      if (!this.searchQuery) return this.leaders;
      const query = this.searchQuery.toLowerCase();
      return this.leaders.filter((leader) => {
        const name = leader.name ? leader.name.toLowerCase() : '';
        const subtitle = leader.subtitle ? leader.subtitle.toLowerCase() : '';
        const civName = leader.civilization && leader.civilization.name
          ? leader.civilization.name.toLowerCase()
          : '';
        return name.includes(query) || subtitle.includes(query) || civName.includes(query);
      });
    },
    totalPages(): number {
      return Math.ceil(this.filteredLeaders.length / this.pageSize);
    },
    paginatedLeaders(): any[] {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredLeaders.slice(start, start + this.pageSize);
    },
  },
  mounted() {
    this.fetchLeaders();
    this.fetchAvailableCivilizations();
    window.addEventListener('keydown', this.handleEscape);
  },
  beforeUnmount() {
    window.removeEventListener('keydown', this.handleEscape);
  },
  methods: {
    handleEscape(e: KeyboardEvent): void {
      if (e.key === 'Escape') {
        if (this.showForm) this.cancelForm();
        if (this.leaderModalVisible) this.closeLeaderModal();
        if (this.civModalVisible) this.closeCivModal();
      }
    },
    fetchLeaders(): void {
      axios
        .get('/api/leaders')
        .then((response) => {
          this.leaders = response.data;
        })
        .catch((error) => console.error('Error fetching leaders:', error));
    },
    fetchAvailableCivilizations(): void {
      axios
        .get('/api/civilizations')
        .then((response) => {
          // Only include civilizations that do not have a leader assigned.
          this.availableCivilizations = response.data.filter((civ: any) => !civ.leader);
        })
        .catch((error) => console.error('Error fetching civilizations:', error));
    },
    openForm(leader?: any): void {
      if (leader) {
        this.editing = true;
        this.currentId = leader.id;
        this.form = { ...leader };
      } else {
        this.editing = false;
        this.currentId = null;
        this.form = {
          name: '',
          civilization_id: '',
          icon: '',
          subtitle: '',
          lifespan: '',
        };
      }
      this.showForm = true;
    },
    cancelForm(): void {
      this.showForm = false;
      this.editing = false;
      this.currentId = null;
      this.form = {
        name: '',
        civilization_id: '',
        icon: '',
        subtitle: '',
        lifespan: '',
      };
    },
    saveLeader(): void {
      // Clear any previous errors
      this.errors = {};
      if (this.editing && this.currentId !== null) {
        axios
          .put(`/api/leaders/${this.currentId}`, this.form)
          .then(() => {
            this.fetchLeaders();
            this.cancelForm();
          })
          .catch((error) => {
            if (error.response && error.response.data.errors) {
              this.errors = error.response.data.errors;
            }
            console.error('Error updating leader:', error);
          });
      } else {
        axios
          .post('/api/leaders', this.form)
          .then(() => {
            this.fetchLeaders();
            this.cancelForm();
          })
          .catch((error) => {
            if (error.response && error.response.data.errors) {
              this.errors = error.response.data.errors;
            }
            console.error('Error creating leader:', error);
          });
      }
    },
    deleteLeader(id: number): void {
      if (confirm('Are you sure you want to delete this leader?')) {
        axios
          .delete(`/api/leaders/${id}`)
          .then(() => this.fetchLeaders())
          .catch((error) => console.error('Error deleting leader:', error));
      }
    },
    viewLeader(id: number): void {
      axios
        .get(`/api/leaders/${id}`)
        .then((response) => {
          this.leaderDetail = response.data;
          this.leaderModalVisible = true;
        })
        .catch((error) => console.error('Error fetching leader detail:', error));
    },
    closeLeaderModal(): void {
      this.leaderModalVisible = false;
      this.leaderDetail = {};
    },
    viewCivilization(civId: number): void {
      axios
        .get(`/api/civilizations/${civId}`)
        .then((response) => {
          this.civDetail = response.data;
          this.civModalVisible = true;
        })
        .catch((error) => console.error('Error fetching civilization details:', error));
    },
    closeCivModal(): void {
      this.civModalVisible = false;
      this.civDetail = {};
    },
    prevPage(): void {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage(): void {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
});
</script>

<style scoped>
</style>
