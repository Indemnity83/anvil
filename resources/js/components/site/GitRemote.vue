<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Update Git Remote
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">

      <div class="mt-2 text-sm leading-5 text-gray-700">
        <p>This will update the Git remote URL on your server; however, the site will not be removed or become unavailable during the process. The updated Git remote must contain the same repository / Git history as the currently installed repository. You should not use this function to install an entirely different project onto this site. If you would like to install an entirely different project, you should completely uninstall the existing repository using the "Uninstall Repository" button below.</p>
      </div>

      <div class="mt-5">
          <form class="w-full" @submit.prevent="updateGitRemote()">

            <fieldset :disabled="busy" :class="{'opacity-50': busy}">

                <div class="sm:flex mt-2 mb-6">
                    <label class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Provider</label>
                    <div class="flex-1 max-w-lg">
                        <div class="">
                            <div class="flex items-center">
                                <input id="github" v-model="form.provider" value="github" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                                <label for="github" class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">Github</span>
                                </label>
                            </div>
                            <div class="mt-2 flex items-center">
                                <input v-model="form.provider" id="custom" value="custom" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                                <label for="custom" class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">Custom</span>
                                </label>
                            </div>
                            <p v-show="form.errors.has('provider')" class="mt-1 text-sm text-red-600" id="provider-error">
                                {{ form.errors.get('provider') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="sm:flex sm:items-center mt-6">
                    <label for="repository" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Repository</label>
                    <div class="flex-1">
                        <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
                            <input id="repository" type="text" class="form-input block w-full sm:text-sm sm:leading-5" :placeholder="repoPlaceholder" v-model="form.repository" :class="{'border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red': form.errors.has('repository')}"/>
                        </div>
                        <p v-show="form.errors.has('repository')" class="mt-1 text-sm text-red-600" id="repository-error">
                            {{ form.errors.get('repository') }}
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
                            Update Git Remote
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
    name: 'GitRemote',
    props: ['site'],
    data() {
        return {
            busy: false,
            form: new Form({
                'provider': this.site.repository_provider,
                'repository': this.site.repository,
            })
        }
    },
    computed: {
        repoPlaceholder() {
            return this.form.provider === 'custom' ? 'git@provider.com:user/repository.git' : 'larave/laravel'
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
                    alert(error.message)
                })
        }
    }
};
</script>
