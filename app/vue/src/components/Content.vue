<template>
    <div class="plan-content">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.name }} </router-link> / Inhalt
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="plan-content-tabs">
                    <h3 :class="{ active: !toggle }" @click="toggle = false">Sychanalyse</h3>
                    <h3 :class="{ active: toggle }" @click="toggle = true">Didaktische Analyse</h3>
                    <div class="plan-content-analysis plan-content-analysis-proper" v-show="!toggle">
                        <NoteElement :element="elementProper" />
                    </div>
                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <NoteElement :element="elementDidactic" />
                    </div>
                </div>
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import NoteElement from './NoteElement.vue';

export default {
    name: 'Content',
    components: {
        InfoBox,
        NoteElement
    },
    data() {
        return {
            toggle: false,
            elementProper: {
                title: 'lorem ipsum',
                content:
                    'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,'
            },
            elementDidactic: {
                title: 'Exemplarische Bedeutung',
                content:
                    'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam'
            }
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        infoBoxTitle() {
            return 'Inhalt';
        }
    },
    methods: {}
};
</script>
