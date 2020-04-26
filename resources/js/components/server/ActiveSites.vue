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
            <tr v-for="site in sites" class="hover:bg-blue-50" :key="site.id">
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-brand-400">
                    <router-link :to="'sites/' + site.id" class="hover:text-brand-600 hover:underline flex items-center">
                        <font-awesome-icon v-if="site.status === 'installing'" :icon="['fas', 'sync-alt']" class="mr-2" spin />
                        {{ site.name }}
                    </router-link>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                    {{ site.port }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700">
                    <span v-if="site.repository_status === 'installing'" class="flex items-center">
                        <font-awesome-icon :icon="['fas', 'sync-alt']" class="mr-2" spin />
                        Installing
                    </span>

                    <span v-if="site.repository_status === 'uninstalling'" class="flex items-center">
                        <font-awesome-icon :icon="['fas', 'sync-alt']" class="mr-2" spin />
                        Uninstalling
                    </span>

                    <span v-if="site.repository_status === 'installed'" class="flex items-center">
                        <font-awesome-icon v-if="site.repository_provider === 'github'" :icon="['fab', 'github']" class="mr-2" /> {{ site.repository }}
                    </span>

                    <span v-if="site.repository_status === null" class="flex items-center">
                        <font-awesome-icon :icon="['far', 'times-circle']" class="mr-2" /> None
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
        props: ['sites'],
    };
</script>
