import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './../components/Home.vue';
import Plan from './../components/Plan.vue';
import Overview from './../components/Overview.vue';
import Situation from './../components/Situation.vue';
import Individual from './../components/Individual.vue';
import Intention from './../components/Intention.vue';
import Content from './../components/Content.vue';
import Methods from './../components/Methods.vue';
import Media from './../components/Media.vue';

Vue.use(VueRouter);
const routes = [
    { path: '/', component: Home },
    {
        path: '/plan/:planId',
        component: Plan,
        children: [
            {
                path: '/',
                component: Overview
            },
            {
                path: 'situation',
                component: Situation
            },
            {
                path: 'individual',
                component: Individual
            },
            {
                path: 'intention',
                component: Intention
            },
            {
                path: 'content',
                component: Content
            },
            {
                path: 'methods',
                component: Methods
            },

            {
                path: 'media',
                component: Media
            }
        ]
    }
];
export default new VueRouter({
    routes
});
