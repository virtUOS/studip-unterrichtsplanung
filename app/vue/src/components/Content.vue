<template>
    <div class="plan-content">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Inhalt
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="plan-content-tabs">
                    <h3 :class="{ active: !toggle }" @click="toggle = false">{{ this.contentStructures[0].name }}</h3>
                    <h3 :class="{ active: toggle }" @click="toggle = true">{{ this.contentStructures[1].name }}</h3>
                    <div class="plan-content-analysis plan-content-analysis-proper" v-show="!toggle">
                        <!-- <NoteElement :element="elementProper" /> -->
                        <NoteElementAdder
                            :structures_id="this.contentStructures[0].id"
                            :elementList="this.properStructures"
                            @addElement="addElement"
                        />
                    </div>
                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <!-- <NoteElement :element="elementDidactic" /> -->
                        <NoteElementAdder
                            :structures_id="this.contentStructures[1].id"
                            :elementList="this.didacticStructures"
                            @addElement="addElement"
                        />
                    </div>
                </div>
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import InfoBox from './InfoBox.vue';
import NoteElement from './NoteElement.vue';
import NoteElementAdder from './NoteElementAdder.vue';

export default {
    name: 'Content',
    components: {
        InfoBox,
        NoteElement,
        NoteElementAdder
    },
    data() {
        return {
            toggle: false,
            elementProper: {
                name: 'lorem ipsum',
                text:
                    'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren,'
            },
            elementDidactic: {
                name: 'Exemplarische Bedeutung',
                text:
                    'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam'
            },
            contentStructures: [],
            properStructures: [],
            didacticStructures: []
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
    created() {
        this.getContentStructures();
    },
    mounted() {},
    methods: {
        getContentStructures() {
            let view = this;
            axios
                .get('./api/structures/4')
                .then(function(response) {
                    response.data.data.forEach(function(struc) {
                        view.contentStructures.push(struc.attributes);
                    });
                    view.getSubstructures();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getSubstructures() {
            let view = this;
            //get A
            axios
                .get('./api/structures/' + view.contentStructures[0].id)
                .then(function(response) {
                    view.properStructures = response.data.data;
                    view.properStructures.forEach(function(element) {
                        element.add = true;
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
            //get B
            axios
                .get('./api/structures/' + view.contentStructures[1].id)
                .then(function(response) {
                    view.didacticStructures = response.data.data;
                    view.didacticStructures.forEach(function(element) {
                        element.add = true;
                    });
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
