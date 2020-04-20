<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Maintenance Mode
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
      
      <div class="mt-2 text-sm leading-5 text-gray-700">
        <p>Putting your site into maintenance mode will make it unavailable to the public.</p>
      </div>

      <div class="mt-5">
        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-danger-500 hover:bg-danger-400 focus:outline-none focus:border-danger-600 focus:shadow-outline-danger active:bg-danger-00 transition ease-in-out duration-150">
          Enable Maintenance Mode
        </button>
      </div>

      </div>
  </div>
</template>

<script>
export default {
  name: 'MaintenanceMode',
};
</script>
