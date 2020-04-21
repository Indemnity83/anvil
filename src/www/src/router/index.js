import Vue from 'vue';
import VueRouter from 'vue-router';
import Server from '../views/Server.vue';
import Site from '../views/Site.vue';

Vue.use(VueRouter);

const routes = [
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
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue'),
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
});

export default router;
