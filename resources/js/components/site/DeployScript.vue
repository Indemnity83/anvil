<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">
    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Deploy Script
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
        <form class="w-full" @submit.prevent="putScript">
            <fieldset :disabled="busy" :class="{'opacity-50': busy}">
      <div class="mt-2 text-sm leading-5 text-gray-700">
        <label for="script" class="sr-only">Deploy Script</label>
        <div class="mt-1 relative rounded-md shadow-sm">
          <textarea class="form-input block w-full sm:text-sm sm:leading-5 font-mono" id="script" v-model="form.content" :placeholder="form.content === null ? 'Loading...' : ''" rows=10>

          </textarea>
        </div>
      </div>

      <div class="mt-5">
        <button :disabled="busy" :class="{'opacity-50': busy}" type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
            <span v-if="busy">
                <font-awesome-icon  :icon="['fas', 'sync-alt']" class="mr-2" spin />
                Working...
            </span>
            <span v-else>
                Save Script
            </span>
        </button>
      </div>
            </fieldset>
        </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Form from "../../form";

export default {
    name: 'DeployScript',
    props: ['site'],
    data() {
        return {
            busy: false,
            loading: false,
            form: new Form({
                content: null,
            })
        };
    },
    computed: {
        endpoint() {
            return `/api/site/${this.site.id}/deployment/script`
        }
    },
    methods: {
        getScript() {
            this.busy = true
            axios.get(this.endpoint)
                .then((res) => {
                    this.form.content = res.data;
                    this.busy = false
                })
                .catch((error) => {
                    this.busy = false
                    alert(error.message);
                });
        },
        putScript() {
            this.busy = true
            this.form.put(this.endpoint)
                .then((data) => {
                    this.form.content = data
                    this.busy = false
                })
                .catch((error) => {
                    this.busy = false
                    alert(error.message);
                });
        },
    },
    created() {
        this.getScript();
    },
};
</script>
