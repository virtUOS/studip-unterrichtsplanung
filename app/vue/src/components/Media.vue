<template>
    <div class="plan-media">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + planId">{{ plan.title }} </router-link> / Medien
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <!--- create component for each note extract from plan--->
                <NoteElement :element="this.element"/>
            </div>
            <InfoBox />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import NoteElement from './NoteElement.vue';

export default {
    name: 'Media',
    components: {
        InfoBox,
        NoteElement
    },
    data() {
        return{
            element: {title: 'lorem ipsum', content: '123'}
        }
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        planId() {
            return this.$route.params.planId;
        }
    }
};
</script>