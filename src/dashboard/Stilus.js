import Vue from 'vue';
import VueRouter from 'vue-router'
import App from './Stilus.vue'
import 'muse-ui/lib/styles/base.less';
import 'muse-ui/lib/styles/theme.less';
import {
  Grid,
  Helpers,
  Button,
  Select
} from 'muse-ui';
import Home from './pages/Home';
import NotFound from './pages/NotFound';

Vue.use(Button);
Vue.use(Select);
Vue.use(Grid);
Vue.use(Helpers);
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        props: {
            message: "Hello World"
        }
    },
    {
        path: '*',
        name: 'NotFound',
        component: NotFound
    }
];

const router = new VueRouter({routes});

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');
