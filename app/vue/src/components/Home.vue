<template>
    <div class="home">
        <h1><span class="nav home"></span></h1>
        <div class="content-wrapper">
            <nav class="homebox homebox-wide plans" v-show="Object.keys(this.plans).length > 0">
                <header>Plan bearbeiten</header>
                <div class="homebox-content">
                    <router-link :to="'/plan/' + plan.id" v-for="plan in this.plans" :key="plan.id">
                        <p class="homebox-link">
                            {{ plan.attributes.name }}
                            <span class="homebox-link-subtitle">
                                - {{ getPlanTemplateName(plan.attributes.templates_id) }}
                            </span>
                            <br />
                            <span class="homebox-link-subtitle plan-mkdate">
                                erstellt am: {{ getFromatedDate(plan.attributes.mkdate) }}
                            </span>
                            <br />
                            <span class="homebox-link-subtitle plan-chdate">
                                geändert am: {{ getFromatedDate(plan.attributes.chdate) }}
                            </span>
                        </p>
                    </router-link>
                </div>
            </nav>
            <nav
                class="homebox add-plan"
                :class="{
                    'homebox-small': Object.keys(this.plans).length > 0,
                    'homebox-fullwidth': Object.keys(this.plans).length === 0
                }"
            >
                <header>Plan hinzufügen</header>
                <div class="homebox-content">
                    <router-link to="/addplan/1"><p class="homebox-link">Bildungswissenschaftlich</p></router-link>
                    <router-link to="/addplan/2"><p class="homebox-link">Fachdidaktik Mathematik</p></router-link>
                    <router-link to="/addplan/3"><p class="homebox-link">Fachdidaktik Sport</p></router-link>
                    <router-link to="/addplan/4"><p class="homebox-link">Fachdidaktik Geologie</p></router-link>
                </div>
            </nav>
            <InfoBox :structureId="structureId" :structureName="structureName" v-if="showInfobox"/>
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import axios from 'axios';
import mixin from './../mixins/mixin.js';

export default {
    name: 'Home',
    mixins: [mixin],
    components: {
        InfoBox
    },
    data() {
        return {
            plans: {},
            structureId: -1,
            structureName: 'Auswählen oder Erstellen',
            showInfobox: false
        };
    },
    mounted() {
        this.getPlans();
        this.getInfos();
    },
    methods: {
        getPlans() {
            let view = this;
            axios
                .get('./api/plans')
                .then(function(response) {
                    if (response.data.data) {
                        view.plans = response.data.data;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getInfos(){
            let view = this;
            axios
                .get('./api/infotexts')
                .then(response => {
                    if (response.data.data.length > 0) {
                        this.$store.state.infos = response.data.data;
                        view.showInfobox = true;
                    } else {
                        this.$store.state.infos = [];
                    }
                })
                .catch(error => console.log(error));
        }
    }
};
</script>
