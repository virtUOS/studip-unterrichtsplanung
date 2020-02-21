import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        plan: {
            attributes: {
                name: '',
                metadata: {
                    typeOfSchool: '',
                    gradeLevel: '',
                    subject: '',
                    topic: '',
                    date: '',
                    time: ''
                }
            }
        },
        plans: [],
        infos: [],
        interdeps: {
            1: {2: false, 3: false, 4: false, 5: false, 6: false},
            2: {1: false, 3: false, 4: false, 5: false, 6: false},
            3: {1: false, 2: false, 4: false, 5: false, 6: false},
            4: {1: false, 2: false, 3: false, 5: false, 6: false},
            5: {1: false, 2: false, 3: false, 4: false, 6: false},
            6: {1: false, 2: false, 3: false, 4: false, 5: false}
        },
    },

    getters: {
        interdeps: (state) => {
            return state.interdeps;
        },

        plan: (state) => {
            return state.plan;
        },

        plans: (state) => {
            return state.plans;
        },

        infos: (state) => {
            return state.infos;
        },
    },

    mutations: {
        plan: (state, newPlan) => {
            state.plan = newPlan;
        }
    },

    actions: {
        interdeps (store, id) {
            axios.get('./api/plans/' + id + '/interdeps').then(({ data }) => {
                store.state.interdeps = data;
            });
        },

        plan (store, id) {
            axios.get('./api/plans/' + id + '/interdeps').then(({ data }) => {
                store.state.plan = data;
            });
        },

        plans (store) {
            axios.get('./api/plans').then(({ data }) => {
                store.state.plans = data.data;
            });
        },

        infos (store) {
            axios.get('./api/infotexts').then(({ data }) => {
                store.state.infos = data.data;
            });
        }
    }
});
export default store;
