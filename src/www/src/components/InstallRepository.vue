<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Install Repository
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

      <div v-if="isInstalling" class="inline-flex items-center justify-center w-full mx-auto py-20 text-center text-2xl">
        <p>Installing Repository</p>
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="spin h-6 w-auto ml-2">
          <path style="rotate(15)" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
      </div>

      <div v-else class="mt-5">
        <form class="w-full">
          <div class="sm:flex mt-2 mb-6">
            <label class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Provider</label>
            <div class="flex-1 max-w-lg">
              <div class="">
                <div class="flex items-center">
                  <input id="github" v-model="form.location" value="github" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                  <label for="github" class="ml-3">
                    <span class="block text-sm leading-5 font-medium text-gray-700">Github</span>
                  </label>
                </div>
                <div class="mt-2 flex items-center">
                  <input v-model="form.location" id="custom" value="custom" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                  <label for="custom" class="ml-3">
                    <span class="block text-sm leading-5 font-medium text-gray-700">Custom</span>
                  </label>
                </div>
              </div>
            </div>
          </div>


          <div class="sm:flex sm:items-center mt-6">
            <label for="repo" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Repository</label>
            <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
              <input id="repo" type="text" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="larave/laravel" v-model="form.repository"/>
            </div>
          </div>


          <div class="sm:flex sm:items-center mt-4">
            <label for="branch" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Branch</label>
            <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
              <input id="branch" type="text" class="form-input block w-full sm:text-sm sm:leading-5" v-model="form.branch" />
            </div>
          </div>


          <div class="sm:flex sm:items-center mt-4">
            <label class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Installation Options</label>
            <div class="relative flex items-start">
              <div class="absolute flex items-center h-8">
                <input id="dependencies" type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" v-model="form.dependencies"/>
              </div>
              <div class="pl-7 text-sm leading-8">
                <label for="dependencies" class="font-medium text-gray-700">Install Composer Dependencies</label>
              </div>
            </div>
          </div>

          <div class="sm:flex sm:items-center mt-5">
            <button @click="install" type="button" class="sm:ml-48 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
              Install Repository
            </button>            
          </div>
        </form>
        
      </div>

      </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'InstallRepository',
  data() {
    return {
      isInstalling: false,
      form: {
        repository: '',
        branch: 'master',
        location: 'github',
        dependencies: true,
      },
    };
  },
  methods: {
    install() {
      this.isInstalling = true;
      const path = '/api/install';
      axios.post(path, this.form)
        .then((res) => {
          // eslint-disable-next-line
          console.log(res.data);
          // TODO: watch the server for when the install is done instead of waiting and refreshing
          this.$router.go(0);
        })
        .catch((error) => {
          this.isInstalling = false;
          // eslint-disable-next-line
          console.error(error);
        });
    },
  },
};
</script>
