import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    // just some dummy content ... soon we need real stuff
    state: {
        plan: {}
    }
    // mutations: {
    //     loadPlan(state, planId) {

    //         if (planId == 1) {
    //             state.plan = { title: 'Plan A' };
    //         } else {
    //             state.plan = { title: 'default Plan' };
    //         }
    //         // todo
    //     }
    // }
});
