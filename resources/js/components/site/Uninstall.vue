<template>
    <!-- eslint-disable -->
    <div class="bg-white overflow-hidden overflow-hidden shadow rounded-lg">
        <div class="px-4 sm:px-6">
            <h3 class="py-5 text-lg leading-6 font-medium text-gray-900">
                Uninstall Repository
            </h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-100">
            <div>
                <div class="mt-2 text-sm leading-5 text-gray-700">
                    <p>Uninstalling a repository will reset the site back to its original state, which is a simple PHP information display.</p>
                </div>
                <div class="mt-5">
                    <button @click="showModal = true" type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-danger-500 hover:bg-danger-400 focus:outline-none focus:border-danger-600 focus:shadow-outline-danger active:bg-danger-00 transition ease-in-out duration-150">
                        Uninstall Repository
                    </button>
                </div>
            </div>
        </div>
        <portal to="modals">
            <div v-show="showModal" class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">
                <transition
                    enter-active-class="ease-out duration-3000"
                    enter-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="showModal" class="fixed inset-0 transition-opacity">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div v-show="showModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Uninstall Repository
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm leading-5 text-gray-500">
                                        Are you sure you want to uninstall this repository?
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-200 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button type="button" @click="onSubmit" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Uninstall
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button type="button" @click="showModal = false" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                    Cancel
                                </button>
                            </span>
                        </div>
                    </div>
                </transition>
            </div>
        </portal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Uninstall',
    props: ['site'],
    data() {
        return {
            showModal: false,
        };
    },
    methods: {
        onSubmit() {
            axios.delete(`/api/site/${this.site.id}/git`)
            .then(response => {
                this.$parent.$emit('SiteUpdated', response.data)
            })
        },
    },
};
</script>
