<template>
    <div class="overview">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / {{ plan.name }}
        </h1>
        <div class="content-wrapper">
            <div class="overview-boxes">
                <OverviewBox
                    :title="'situative Voraussetzungen'"
                    :interdep="interdepInhalt"
                    :catName="'situation'"
                    :planId="plan.id"
                />
                <OverviewBox
                    :title="'individuelle Voraussetzungen'"
                    :interdep="interdepInhalt"
                    :catName="'individual'"
                    :planId="plan.id"
                />
                <OverviewBox
                    :title="'Intentionalität'"
                    :interdep="interdepInhalt"
                    :catName="'intention'"
                    :planId="plan.id"
                />
                <OverviewBox :title="'Inhalt'" :interdep="interdepInhalt" :catName="'content'" :planId="plan.id" />
                <OverviewBox :title="'Methodik'" :interdep="interdepInhalt" :catName="'method'" :planId="plan.id" />
                <OverviewBox :title="'Medien'" :interdep="interdepInhalt" :catName="'media'" :planId="plan.id" />
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