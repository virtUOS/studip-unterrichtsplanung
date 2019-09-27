<template>
  <div class="overview">
      <h1><router-link to='/'><span class="nav home"></span></router-link> / {{plan.title}}</h1>
    <div class="content-wrapper">
        <div class="overview-boxes">
            <OverviewBox :title="'situative Voraussetzungen'" :interdep="interdepInhalt" :catName="'situation'" :planId="planId"/>
            <OverviewBox :title="'individuelle Voraussetzungen'" :interdep="interdepInhalt" :catName="'individual'" :planId="planId"/>
            <OverviewBox :title="'Intentionalität'" :interdep="interdepInhalt" :catName="'intention'" :planId="planId" />
            <OverviewBox :title="'Inhalt'" :interdep="interdepInhalt" :catName="'content'" :planId="planId" />
            <OverviewBox :title="'Methodik'" :interdep="interdepInhalt" :catName="'method'" :planId="planId" />
            <OverviewBox :title="'Medien'" :interdep="interdepInhalt" :catName="'media'" :planId="planId" />
        </div>
        <InfoBox />
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
            interdepInhalt: {"Inhalt": false, "Medien": false},
        };
    },
    computed: {
        plan() {
            this.$store.commit('loadPlan', this.planId)
            return this.$store.state.plan;
        },
        planId() {
            return this.$route.params.planId;
        }
    },
}
</script>