<template>
    <div class="overview">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / {{ plan.attributes.name }}
            <router-link :to="'/editplan/' + plan.id"><span class="nav edit-plan"></span></router-link>
        </h1>
        <div class="content-wrapper">
            <div class="overview-boxes">
                <OverviewBox
                    :title="'situative Voraussetzungen'"
                    :strucutres_id="1"
                    :catName="'situation'"
                    :planId="plan.id"
                />
                <OverviewBox
                    :title="'individuelle Voraussetzungen'"
                    :strucutres_id="2"
                    :catName="'individual'"
                    :planId="plan.id"
                />
                <OverviewBox :title="'Intentionalität'" :strucutres_id="3" :catName="'intention'" :planId="plan.id" />
                <OverviewBox :title="'Inhalt'" :strucutres_id="4" :catName="'content'" :planId="plan.id" />
                <OverviewBox :title="'Methodik'" :strucutres_id="5" :catName="'method'" :planId="plan.id" />
                <OverviewBox :title="'Medien'" :strucutres_id="6" :catName="'media'" :planId="plan.id" />
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import OverviewBox from './OverviewBox.vue';

export default {
    name: 'Overview',
    components: {
        InfoBox,
        OverviewBox
    },
    data() {
        return {
            interdepInhalt: { Inhalt: false, Medien: false }
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        infoBoxTitle() {
            return 'Planübersicht';
        }
    }
};
</script>
