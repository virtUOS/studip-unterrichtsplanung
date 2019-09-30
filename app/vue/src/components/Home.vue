<template>
    <div class="home">
        <h1><span class="nav home"></span></h1>
        <div class="content-wrapper">
            <nav class="homebox plans">
                <header>Plan bearbeiten</header>
                <router-link
                    :to="'/plan/' + plan.id"
                    v-for="plan in this.plans"
                    :key="plan.id"
                    @click.native="setPlan(plan)"
                >
                    <p class="homebox-link">
                        {{ plan.name }} <span class="homebox-link-subtitle">- {{ plan.templates_id }}</span>
                    </p>
                </router-link>
            </nav>
            <nav class="homebox add-plan">
                <header>Plan hinzufügen</header>
                <router-link to="/addplan/1"><p class="homebox-link">Bildungswissenschaftlich</p></router-link>
                <router-link to="/addplan/2"><p class="homebox-link">Fachdidaktik Mathematik</p></router-link>
                <router-link to="/addplan/3"><p class="homebox-link">Fachdidaktik Sport</p></router-link>
                <router-link to="/addplan/4"><p class="homebox-link">Fachdidaktik Geologie</p></router-link>
            </nav>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import axios from 'axios';

export default {
    name: 'Home',
    components: {
        InfoBox
    },
    data() {
        return {
            plans: this.getPlans()
        };
    },
    computed: {
        infoBoxTitle() {
            return 'Auswählen oder Erstellen';
        }
    },
    methods: {
        getPlans() {
            let view = this;
            axios
                .get('./api/plans')
                .then(function(response) {
                    view.plans = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        setPlan(plan) {
            // use this until router takes care
            this.$store.state.plan = plan;
        }
    }
};
</script>
