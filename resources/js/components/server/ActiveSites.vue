<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Active Sites
      </h3>
    </div>

    <div class="border-t border-gray-200 bg-gray-100">
        <table class="min-w-full">
            <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Domain
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Port
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    App
                </th>
            </tr>
            </thead>
            <tbody class="bg-white">
            <tr v-for="site in sites" class="hover:bg-blue-50">
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-brand-400">
                    <router-link :to="'sites/' + site.id" class="hover:text-brand-600 hover:underline flex items-center">
                        <svg v-if="site.status === 'installing'" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="mr-2 spin h-5 w-auto ml-2">
                            <path style="rotate(15)" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ site.name }}
                    </router-link>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                    {{ site.port }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                    <span v-if="site.app">
                        {{ site.repository }}
                    </span>

                    <span v-else class="flex items-center">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="text-gray-600 w-5 h-5 mr-1">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        None
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

  </div>
</template>

<script>
    export default {
        name: 'ActiveSites',
        data() {
            return {
                sites: [],
            }
        },
        methods: {
            getSites() {
                axios.get('/api/site')
                    .then(response => this.sites = response.data)
            },
        },
        created() {
            this.getSites();
        }
    };
</script>
