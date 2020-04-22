<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Uninstall Repository
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

      <div v-if="isUninstalling" class="inline-flex items-center justify-center w-full mx-auto py-20 text-center text-2xl">
        <p>Uninstalling Repository</p>
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="spin h-6 w-auto ml-2">
          <path style="rotate(15)" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
      </div>
      
      <div v-else>
        <div class="mt-2 text-sm leading-5 text-gray-700">
          <p>Uninstalling a repository will reset the site back to its original state, which is a simple PHP information display.</p>
        </div>

        <div class="mt-5">
          <button @click="uninstall" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-danger-500 hover:bg-danger-400 focus:outline-none focus:border-danger-600 focus:shadow-outline-danger active:bg-danger-00 transition ease-in-out duration-150">
            Uninstall Repository
          </button>
        </div>
      </div>

      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Uninstall',
  data() {
    return {
      isUninstalling: false,
    };
  },
  methods: {
    uninstall() {
      this.isUninstalling = true;
      const path = '/api/install';
      axios.delete(path)
        .then((res) => {
          // eslint-disable-next-line
          console.log(res.data);
          this.$router.go(0);
        })
        .catch((error) => {
          this.isUninstalling = false;
          // eslint-disable-next-line
          console.error(error);
        });
    },
  },
};
</script>
