<template>
  <div class="p-4">
    <!-- Combined Header Row -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
      <h2 class="text-2xl font-bold mb-2 sm:mb-0">Civilizations</h2>
      <div class="flex items-center space-x-4">
        <SearchFilter v-model="searchQuery" placeholder="Search civilizations or leader" />
        <button
          @click="openForm()"
          class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer"
        >
          Create New Civilization
        </button>
      </div>
    </div>

    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 border-b border-gray-200">ID</th>
          <th class="py-2 border-b border-gray-200">Civilization</th>
          <th class="py-2 border-b border-gray-200">Leader</th>
          <th class="py-2 border-b border-gray-200">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="civ in paginatedCivilizations"
          :key="civ.id"
          class="hover:bg-gray-50"
        >
          <td class="px-4 py-2 border-b border-gray-200">{{ civ.id }}</td>
          <td class="px-4 py-2 border-b border-gray-200">
            <div class="flex items-center">
              <img :src="civ.icon" alt="icon" class="w-8 h-8 mr-2" />
              <span class="text-blue-600 cursor-pointer" @click="viewHistory(civ.id)">
                {{ civ.name }}
              </span>
            </div>
          </td>
          <td class="px-4 py-2 border-b border-gray-200">
            <span
              v-if="civ.leader"
              class="text-blue-600 cursor-pointer"
              @click="viewLeader(civ.leader.id)"
            >
              {{ civ.leader.name }}
            </span>
            <span v-else class="text-gray-500 text-sm">None</span>
          </td>
          <td class="px-4 py-2 border-b border-gray-200">
            <button
              @click="openForm(civ)"
              class="bg-blue-500 text-white px-2 py-1 rounded mr-2 cursor-pointer"
            >
              Edit
            </button>
            <button
              @click="deleteCivilization(civ.id)"
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

    <!-- Modal for Create/Edit Civilization Form -->
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
          {{ editing ? "Edit Civilization" : "Create New Civilization" }}
        </h3>
        <form @submit.prevent="saveCivilization">
          <div class="mb-4">
            <label class="block mb-1">Name:</label>
            <input
              type="text"
              v-model="form.name"
              class="border px-3 py-2 w-full"
              required
            />
          </div>
          <div class="mb-4">
            <label class="block mb-1">Icon URL:</label>
            <input
              type="text"
              v-model="form.icon"
              class="border px-3 py-2 w-full"
              required
            />
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

    <!-- Modal for Viewing Civilization Historical Info -->
    <div
      v-if="civHistoryModalVisible"
      @click.self="closeCivHistoryModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
    >
      <div class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto relative">
        <button
          @click="closeCivHistoryModal"
          class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 font-bold text-xl"
        >
          &times;
        </button>
        <h3 class="text-xl font-bold mb-4">Civilization History</h3>
        <ul>
          <li
            v-for="(info, index) in civHistory"
            :key="index"
            class="border-b border-gray-200 py-2"
          >
            <strong>{{ info.heading }}:</strong> {{ info.text }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Modal for Viewing Leader Details (from Civilization page) -->
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
  </div>
</template>

<script>
import axios from "axios";
import SearchFilter from "@/components/SearchFilter.vue";
import PaginationControls from "@/components/PaginationControls.vue";

export default {
  name: "Civilizations",
  components: {
    SearchFilter,
    PaginationControls,
  },
  data() {
    return {
      civilizations: [],
      showForm: false,
      editing: false,
      form: {
        name: "",
        icon: "",
      },
      currentId: null,
      civHistoryModalVisible: false,
      civHistory: [],
      leaderModalVisible: false,
      leaderDetail: {},
      searchQuery: "",
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
    filteredCivilizations() {
      if (!this.searchQuery) return this.civilizations;
      const query = this.searchQuery.toLowerCase();
      return this.civilizations.filter((civ) => {
        const civName = civ.name ? civ.name.toLowerCase() : "";
        const leaderName =
          civ.leader && civ.leader.name ? civ.leader.name.toLowerCase() : "";
        return civName.includes(query) || leaderName.includes(query);
      });
    },
    totalPages() {
      return Math.ceil(this.filteredCivilizations.length / this.pageSize);
    },
    paginatedCivilizations() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredCivilizations.slice(start, start + this.pageSize);
    },
  },
  mounted() {
    this.fetchCivilizations();
    window.addEventListener("keydown", this.handleEscape);
  },
  beforeUnmount() {
    window.removeEventListener("keydown", this.handleEscape);
  },
  methods: {
    handleEscape(e) {
      if (e.key === "Escape") {
        if (this.showForm) this.cancelForm();
        if (this.civHistoryModalVisible) this.closeCivHistoryModal();
        if (this.leaderModalVisible) this.closeLeaderModal();
      }
    },
    fetchCivilizations() {
      axios
        .get("/api/civilizations")
        .then((response) => {
          this.civilizations = response.data;
        })
        .catch((error) =>
          console.error("Error fetching civilizations:", error)
        );
    },
    openForm(civ = null) {
      if (civ) {
        this.editing = true;
        this.currentId = civ.id;
        this.form = {
          name: civ.name,
          icon: civ.icon,
        };
      } else {
        this.editing = false;
        this.currentId = null;
        this.form = { name: "", icon: "" };
      }
      this.showForm = true;
    },
    cancelForm() {
      this.showForm = false;
      this.editing = false;
      this.currentId = null;
      this.form = { name: "", icon: "" };
    },
    saveCivilization() {
      if (this.editing) {
        axios
          .put(`/api/civilizations/${this.currentId}`, this.form)
          .then(() => {
            this.fetchCivilizations();
            this.cancelForm();
          })
          .catch((error) =>
            console.error("Error updating civilization:", error)
          );
      } else {
        axios
          .post("/api/civilizations", this.form)
          .then(() => {
            this.fetchCivilizations();
            this.cancelForm();
          })
          .catch((error) =>
            console.error("Error creating civilization:", error)
          );
      }
    },
    deleteCivilization(id) {
      if (confirm("Are you sure you want to delete this civilization?")) {
        axios
          .delete(`/api/civilizations/${id}`)
          .then(() => this.fetchCivilizations())
          .catch((error) =>
            console.error("Error deleting civilization:", error)
          );
      }
    },
    viewHistory(civId) {
      axios
        .get(`/api/civilizations/${civId}`)
        .then((response) => {
          this.civHistory = response.data.historical_info || [];
          this.civHistoryModalVisible = true;
        })
        .catch((error) =>
          console.error("Error fetching civilization history:", error)
        );
    },
    closeCivHistoryModal() {
      this.civHistoryModalVisible = false;
      this.civHistory = [];
    },
    viewLeader(id) {
      axios
        .get(`/api/leaders/${id}`)
        .then((response) => {
          this.leaderDetail = response.data;
          this.leaderModalVisible = true;
        })
        .catch((error) =>
          console.error("Error fetching leader detail:", error)
        );
    },
    closeLeaderModal() {
      this.leaderModalVisible = false;
      this.leaderDetail = {};
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
  },
};
</script>

<style scoped>
</style>
