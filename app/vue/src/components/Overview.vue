<template>
  <div class="overview">
      <h1>{{plan.title}} {{planId}}: Übersicht</h1>
    <div class="content-wrapper">
        <div class="overview-boxes">
            <OverviewBox :title="'situative Voraussetzungen'" :state="stateInhalt" :catName="'situation'" :planId="planId"/>
            <OverviewBox :title="'individuelle Voraussetzungen'" :state="stateInhalt" :catName="'individual'" :planId="planId"/>
            <OverviewBox :title="'Intentionalität'" :state="stateInhalt" :catName="'intention'" :planId="planId" />
            <OverviewBox :title="'Inhalt'" :state="stateInhalt" :catName="'content'" :planId="planId" />
            <OverviewBox :title="'Methodik'" :state="stateInhalt" :catName="'methods'" :planId="planId" />
            <OverviewBox :title="'Medien'" :state="stateInhalt" :catName="'media'" :planId="planId" />
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
    props: {
    },
    data() {
        return {
            stateInhalt: {"Inhalt": false, "Medien": false},
            plan: {'title': 'Plan with routes'}
        };
    },
    computed: {
        count() {
            this.$store.commit('increment');
            return this.$store.state.count;
        },
        planId() {
            return this.$route.params.planId;
        }
    },
}
</script>

<style>

</style>