import 'es6-promise/auto' // for vuex
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import router from './router/index'
import i18n from './plugins/i18n'
import vuetify from './plugins/vuetify'
import 'roboto-fontface/css/roboto/roboto-fontface.css'
import '@mdi/font/css/materialdesignicons.css'
import {createProvider} from './plugins/vue-apollo'
import store from './plugins/vuex'

Vue.use(VueRouter);

Vue.config.productionTip = false;

// todo init vuex

new Vue({
    router,
    vuetify,
    store,
    apolloProvider: createProvider(),
    i18n,
    render: h => h(App)
}).$mount('#app');
