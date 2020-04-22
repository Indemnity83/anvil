<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <div class="sm:flex sm:items-center sm:justify-between">
        <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
          Deployment
        </h3>
        <span class="inline-flex rounded-md shadow-sm">
          <button @click="deployNow" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
              <font-awesome-icon v-if="isDeploying" :icon="['fas', 'sync-alt']" class="mr-2" spin />
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
