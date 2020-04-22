<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        Deployment Trigger URL
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
      
      <div class="mt-2 text-sm leading-5 text-gray-700">
        <p>Using a custom Git service, or want a service like Travis CI to run your tests before your application is deployed to Anvil? It's simple. When you commit fresh code, or when your continuous integration service finishes testing your application, instruct the service to make a GET or POST request to the following URL. Making a request to this URL will trigger your Anvil deployment script:</p>
        
        <div class="rounded-md bg-warning-100 border-warning-300 border py-3 px-3 sm:px-6 mt-4">
          <div class="text-sm leading-5 text-warning-700">
            <p>https://forge.laravel.com/servers/276585/sites/1014130/deploy/http?token=N0zAXHWCzvVByrYhvfZyaB0OLUc3DII9E2rJeblD</p>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
          Refresh Site Token
        </button>
      </div>

      </div>
  </div>
</template>

<script>
export default {
  name: 'DeployTrigger',
};
</script>
