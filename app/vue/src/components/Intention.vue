<template>
    <div class="plan-intention">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.name }} </router-link> / Intentionalität
        </h1>
        <div class="content-wrapper">
            <div class="content-container"></div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';

export default {
    name: 'Intention',
    components: {
        InfoBox
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
