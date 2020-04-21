import Vue from 'vue'
import VueRouter from 'vue-router'
import PortalVue from 'portal-vue'

Vue.use(VueRouter)
Vue.use(PortalVue)

import App from './views/App'
import Server from './views/Server'
import Site from './views/Site'
import About from './views/About'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Server',
            component: Server,
        },
        {
            path: '/site',
            name: 'Site',
            component: Site,
        },
        {
            path: '/about',
            name: 'About',
            component: About,
        },
    ],
});


const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
