<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">
    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Deploy Script
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
      
      <div class="mt-2 text-sm leading-5 text-gray-700">
        <label for="script" class="sr-only">Deploy Script</label>
        <div class="mt-1 relative rounded-md shadow-sm">
          <textarea class="form-input block w-full sm:text-sm sm:leading-5 font-mono" v-model="deployScript" rows=10>
            
          </textarea>
        </div>
      </div>

      <div class="mt-5">
        <button @click="saveDeployScript" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
          Save Script
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'DeployScript',
  data() {
    return {
      deployScript: '',
    };
  },
  methods: {
    getDeployScript() {
      const path = '/api/deploy/script';
      axios.get(path)
        .then((res) => {
          this.deployScript = res.data;
        })
        .catch((error) => {
          // eslint-disable-next-line
          console.error(error);
        });
    },
    saveDeployScript() {
      const path = '/api/deploy/script';
      axios.post(path, {
        script: this.deployScript,
      })
        .then((res) => {
          // eslint-disable-next-line
          console.log(res.data);
        })
        .catch((error) => {
          // eslint-disable-next-line
          console.error(error);
        });
    },
  },
  created() {
    this.getDeployScript();
  },
};
</script>
