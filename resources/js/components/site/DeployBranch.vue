<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Deployment Branch
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

      <div class="mt-2 text-sm leading-5 text-gray-700">
        <p>Anvil uses this branch to gather details of the latest commit when you deploy your application. You should verify that this branch matches the branch in your deployment script and the branch that is actually deployed on your server.</p>
      </div>

      <div class="mt-5">
        <form class="w-full" @submit.prevent="updateGitRemote()">
            <fieldset :disabled="busy" :class="{'opacity-50': busy}">

                <div class="sm:flex sm:items-center mt-6">
                    <label for="branch" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Repository</label>
                    <div class="flex-1">
                        <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                            <input id="branch" type="text" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="master" v-model="form.branch" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('branch')}"/>
                        </div>
                        <p v-show="form.errors.has('branch')" class="mt-1 text-sm text-red-600" id="branch-error">
                            {{ form.errors.get('branch') }}
                        </p>
                    </div>
                </div>


                <div class="sm:flex sm:items-center mt-5">
                    <button type="submit" :disabled="busy" class="sm:ml-48 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
                        <span v-if="busy">
                            <font-awesome-icon  :icon="['fas', 'sync-alt']" class="mr-2" spin />
                            Working...
                        </span>
                        <span v-else>
                            Update Branch
                        </span>
                    </button>
                </div>

            </fieldset>
        </form>



      </div>

      </div>
  </div>
</template>

<script>
import Form from "../../form";

export default {
    name: 'DeployBranch',
    props: ['site'],
    data() {
        return {
            busy: false,
            form: new Form({
                'branch': this.site.repository_branch,
            })
        }
    },
    methods: {
        updateGitRemote() {
            this.busy = true
            this.form.put(`/api/site/${this.site.id}/git`)
                .then(response => {
                    this.$parent.$emit('SiteUpdated', response)
                    this.busy = false
                })
                .catch(error => {
                    this.busy = false
                })
        }
    }
};
</script>
