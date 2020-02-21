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
        getInfoText: (state) => (id) => {
            let info = state.infos.find(x => x.attributes.structures_id == id);
            if ((info != undefined) && (info.attributes.text != '')){
                return info.attributes.text;
            } else {
                return '<p class="infobox-nodata">Informationen konnten nicht geladen werden.</p>';
            }
        },

        interdeps: (state) => {
            return state.interdeps;
        },

        plan: (state) => {
            return state.plan;
        }
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
                store.state.interdeps = data;
            });
        }
    }
});
export default store;
