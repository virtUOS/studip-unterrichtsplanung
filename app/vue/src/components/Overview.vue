<template>
    <div class="overview">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / {{ plan.attributes.name }}
        </h1>
        <div class="content-wrapper">
            <div class="overview-boxes">
                <OverviewBox
                    :title="'situative Voraussetzungen'"
                    :structures_id="1"
                    :catName="'situation'"
                    :planId="plan.id"
                />
                <OverviewBox
                    :title="'individuelle Voraussetzungen'"
                    :structures_id="2"
                    :catName="'individual'"
                    :planId="plan.id"
                />
                <OverviewBox :title="'Intentionalität'" :structures_id="3" :catName="'intention'" :planId="plan.id" />
                <OverviewBox :title="'Inhalt'" :structures_id="4" :catName="'content'" :planId="plan.id" />
                <OverviewBox :title="'Methodik'" :structures_id="5" :catName="'method'" :planId="plan.id" />
                <OverviewBox :title="'Medien'" :structures_id="6" :catName="'media'" :planId="plan.id" />
            </div>
            <div class="box-wrapper">
                <ToolBox />
                <InfoBox :structureId="structureId" :structureName="structureName" />
            </div>
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import ToolBox from './ToolBox.vue';
import OverviewBox from './OverviewBox.vue';

export default {
    name: 'Overview',
    components: {
        InfoBox,
        ToolBox,
        OverviewBox
    },
    data() {
        return {
            structureName: 'Planübersicht',
            structureId: -3
        };
    },

    mounted() {
        this.$store.dispatch('interdeps', this.plan.id);
    },

    computed: {
        plan() {
            return this.$store.state.plan;
        }
    }
};
</script>
