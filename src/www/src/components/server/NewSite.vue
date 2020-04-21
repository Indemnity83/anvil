<template>
  <!-- eslint-disable -->
  <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">

    <div class="px-4 sm:px-6">
      <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
        New Site
      </h3>
    </div>

    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
      
      <div class="mt-2 text-sm leading-5 text-gray-700">
        <p>Think of sites as representing each "domain" on your server.</p>
      </div>

      <div class="mt-5">
        <form class="w-full">
          
          <div class="sm:flex sm:items-center mt-4">
            <label for="domain" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Root Domain</label>
            <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
              <input id="domain" class="form-input block w-full sm:text-sm sm:leading-5" />
            </div>
          </div>

          <div class="sm:flex sm:items-center mt-5">
            <button type="button" class="sm:ml-48 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
              Add Site
            </button>            
          </div>
        </form>


        
      </div>

      </div>
  </div>
</template>

<script>
export default {
  name: 'NewSite',
};
</script>
