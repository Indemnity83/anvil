<template>
  <!-- eslint-disable -->
  <button @click="showLog">
    <slot></slot>

    <portal to="modals">
      <transition>
      <div v-show="showModal" class="fixed bottom-0 inset-x-0 px-4 pb-6 sm:inset-0 sm:p-0 sm:flex sm:items-center sm:justify-center">

        <transition
          enter-active-class="ease-out duration-300"
          enter-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="ease-in duration-200"
          leave-class="opacity-100"
          leave-to-class="opacity-0"
        >
        <div v-show="showModal" class="fixed inset-0 transition-opacity">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        </transition>

        <transition
          enter-active-class="ease-out duration-300"
          enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to-class="opacity-100 translate-y-0 sm:scale-100"
          leave-active-class="ease-in duration-200"
          leave-class="opacity-100 translate-y-0 sm:scale-100"
          leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
        <div v-show="showModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full">
          <div class="px-4 sm:px-6">
            <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
              Deployment Log
            </h3>
          </div>

          <div class="border-t border-gray-200 pt-1 pb-4 px-4">

            <div v-if="isLoading" class="inline-flex items-center justify-center w-full mx-auto pt-6 text-center">
              <font-awesome-icon :icon="['fas', 'sync-alt']" class="mr-2" spin />
              <p>Loading</p>
            </div>

            <div v-else class="mt-2 text-sm leading-5 text-gray-700">
              <label for="script" class="sr-only">Deploy Script</label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <textarea class="form-input block w-full sm:text-sm sm:leading-5 font-mono bg-gray-100" v-model="deployLog" rows=10 disabled>

                </textarea>
              </div>
            </div>

            <div class="mt-3 text-right">
              <button @click="showModal = false" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
                Close
              </button>
            </div>

          </div>

        </div>
      </transition>
    </div>
      </transition>
    </portal>
  </button>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Deployment',
  data() {
    return {
      showModal: false,
      isLoading: true,
      deployLog: '',
    };
  },
  methods: {
    showLog() {
      this.isLoading = true;
      this.showModal = true;
      const path = '/api/deploy/log';
      axios.get(path)
        .then((res) => {
          this.deployLog = res.data;
          this.isLoading = false;
        })
        .catch((error) => {
          // eslint-disable-next-line
          console.error(error);
        });
    },
  },
};
</script>
