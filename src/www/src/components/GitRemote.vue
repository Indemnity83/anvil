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
        <form class="w-full">
          <div class="sm:flex mt-2">
            <label for="email" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Provider</label>
            <div class="flex-1 max-w-lg">
              <div class="">
                <div class="flex items-center">
                  <input id="push_everything" name="form-input push_notifications" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                  <label for="push_everything" class="ml-3">
                    <span class="block text-sm leading-5 font-medium text-gray-700">Github</span>
                  </label>
                </div>
                <div class="mt-2 flex items-center">
                  <input id="push_email" name="form-input push_notifications" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out" />
                  <label for="push_email" class="ml-3">
                    <span class="block text-sm leading-5 font-medium text-gray-700">Custom</span>
                  </label>
                </div>
              </div>
            </div>
          </div>


          <div class="sm:flex sm:items-center mt-4">
            <label for="email" class="w-40 md:w-48 pr-8 block text-sm sm:text-right font-medium leading-5 text-gray-700">Repository</label>
            <div class="flex-1 mt-1 max-w-lg relative rounded-md shadow-sm">
              <input id="email" class="form-input block w-full sm:text-sm sm:leading-5" />
            </div>
          </div>

          <div class="sm:flex sm:items-center mt-5">
            <button type="button" class="sm:ml-48 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-400 focus:outline-none focus:border-brand-600 focus:shadow-outline-brand active:bg-brand-00 transition ease-in-out duration-150">
              Update Git Remote
            </button>            
          </div>
        </form>


        
      </div>

      </div>
  </div>
</template>

<script>
export default {
  name: 'DeployBranch',
};
</script>
