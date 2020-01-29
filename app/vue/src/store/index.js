import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        plan: {},
        infos: []
    },
    getters: {
        getInfoText: (state) => (id) => {
            let info = state.infos.find(x => x.attributes.structures_id == id);
            if (info != undefined) {
                return info.attributes.text;
            } else {
                return '<p class="infobox-nodata">Informationen konnten nicht geladen werden.</p>';
            }
        }
    }
});
export default store;
