/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

window.axios = require('axios');

let metaTag = document.querySelector('meta[name="access-token"]').content;

axios.defaults.headers.common['Authorization'] = `Bearer ${metaTag}`;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import './app.css';
import Vuex from 'vuex';
import App from './components/App.vue';
import { router } from './route';

Vue.component('app-component', App);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//let token = document.querySelector('meta[name="csrf-token"]').content;

Vue.use(Vuetify);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: { count: 0, store: [], products: [] },
    getters: {
        store: state => {
            return state.store
        },
        products: state => {
            return state.products
        }
    },
    mutations: {
        INCREASE(state) {
            state.count++
        },
        GET_STORE(state, store) {
            return state.store = store;
        },
        GET_PRODUCTS(state, products) {
            return state.products = products;
        }
    },
    actions: {
        fetchStores: ({ commit }) => {
            axios
                .get('/get_stores?type=option')
                .then(response => commit('GET_STORE', response.data));
        },
        fetchProducts: ({ commit }) => {
            axios
                .get('/get_products?type=option')
                .then(response => commit('GET_PRODUCTS', response.data));
        }
    }
});

const vuetify = new Vuetify({
    theme: {
        themes: {
            light: {
                primary: '#1E88E5',
                secondary: '#696969',
                accent: '#8c9eff',
                error: '#b71c1c',
            },
            dark: {
                primary: '#b71c1c',
                secondary: '#696969',
                accent: '#8c9eff',
                error: '#b71c1c',
            },
        },
    },
});

new Vue({
    router,
    vuetify,
    store,
  //   el: '#app',
}).$mount('#app');
