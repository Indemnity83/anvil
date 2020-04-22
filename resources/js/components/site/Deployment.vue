<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <div class="sm:flex sm:items-center sm:justify-between">
        <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
          Deployment
        </h3>
        <span class="inline-flex rounded-md shadow-sm">


          <button v-if="isDeploying" @click="deployNow" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-300">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="spin h-5 w-auto mr-2">
              <path style="rotate(15)" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Deploy Now
          </button>

          <button v-else @click="deployNow" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
            Deploy Now
          </button>
        </span>
      </div>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

      <div class="mt-2 text-sm leading-5 text-gray-700">
        <!-- some kind of description here -->
      </div>

      <div class="mt-5 text-right">
        <deployment-log class="font-light text-brand-500 hover:text-brand-400 transition ease-in-out duration-150">
          View Latest Deployment Log
        </deployment-log>
      </div>

      </div>
  </div>
</template>

<script>
import DeploymentLog from './DeploymentLog';
import axios from 'axios';

export default {
  name: 'Deployment',
  data() {
    return {
      isDeploying: false,
    };
  },
  methods: {
    deployNow() {
      this.isDeploying = true;
      const path = '/api/deploy';
      axios.post(path)
        .then((res) => {
          this.isDeploying = false;
          // eslint-disable-next-line
          console.log(res);
        })
        .catch((error) => {
          this.isDeploying = false;
          // eslint-disable-next-line
          console.error(error);
        });
    },
  },
  components: {
    DeploymentLog,
  },
};
</script>

<style>
.spin {
  animation: rotation 2s infinite linear;
}
@keyframes rotation {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(359deg);
  }
}
</style>
