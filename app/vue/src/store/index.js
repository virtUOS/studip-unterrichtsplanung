import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    // just some dummy content ... soon we need real stuff
    state: {
        count: 0
    },
    mutations: {
        increment(state) {
            state.count++;
        }
    }
});
