import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import Home from './../components/Home.vue';
import Plan from './../components/Plan.vue';
import EditPlan from './../components/EditPlan.vue';
import Overview from './../components/Overview.vue';
import Situation from './../components/Situation.vue';
import Individual from './../components/Individual.vue';
import Intention from './../components/Intention.vue';
import Content from './../components/Content.vue';
import Method from './../components/Method.vue';
import Media from './../components/Media.vue';
import Schedule from './../components/Schedule.vue';
import store from './../store/index.js';

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
                path: 'method',
                component: Method
            },
            {
                path: 'media',
                component: Media
            }
        ]
    },
    {
        path: '/addplan/:planType',
        component: EditPlan,
        props: { newplan: true }
    },
    {
        path: '/editplan/:planId',
        component: EditPlan,
        props: { newplan: false }
    },
    {
        path: '/editschedule/:planId',
        component: Schedule
    }
];
var router = new VueRouter({
    routes
});
router.beforeEach((to, from, next) => {
    if (to.params.planId) {
        if (to.params.planId == store.state.plan.id) {
            next();
        } else {
            var plans = [];
            axios
                .get('./api/plans')
                .then(function(response) {
                    plans = response.data.data;
                    plans.forEach(function(plan) {
                        if (to.params.planId == plan.id) {
                            store.state.plan = plan;
                            store.state.plan.attributes.metadata = JSON.parse(store.state.plan.attributes.metadata);
                            return true;
                        }
                    });
                    next();
                })
                .catch(function(error) {
                    console.log(error);
                });
            axios
                .get('./api/infotexts/' + store.state.plan.attributes.templates_id)
                .then(response => {
                    if (response.data.data.length > 0) {
                        store.state.infos = response.data.data;
                    } else {
                        store.state.infos = [];
                    }
                })
                .catch(error => console.log(error));
        }
    } else {
        next();
    }
});
export default router;
