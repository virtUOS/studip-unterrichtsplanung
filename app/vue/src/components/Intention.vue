<template>
    <div class="plan-intention">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Intentionalität
        </h1>
        <div class="content-wrapper">
            <div class="content-container"></div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="3" :title="'Interdependenzen'" />
                <InfoBox :title="infoBoxTitle" />
            </div>
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import InterdepBox from './InterdepBox';

export default {
    name: 'Intention',
    components: {
        InfoBox,
        InterdepBox
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        infoBoxTitle() {
            return 'Intentionalität';
        }
    }
};
</script>
