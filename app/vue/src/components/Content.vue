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
                        <NoteElement
                            :element="elementProper"
                            v-for="elementProper in elementsProper"
                            :key="elementProper.id"
                        />
                        <NoteElementAdder
                            :structures_id="this.contentStructures[0].id"
                            :elementList="this.properStructures"
                            @addElement="addElement"
                        />
                    </div>
                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <NoteElement
                            :element="elementDidactic"
                            v-for="elementDidactic in elementsDidactic"
                            :key="elementDidactic.id"
                        />
                        <NoteElementAdder
                            :structures_id="this.contentStructures[1].id"
                            :elementList="this.didacticStructures"
                            @addElement="addElement"
                        />
                    </div>
                </div>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="4" :title="'Interdependenzen'" />
                <InfoBox :title="infoBoxTitle" />
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import InfoBox from './InfoBox.vue';
import InterdepBox from './InterdepBox';
import NoteElement from './NoteElement.vue';
import NoteElementAdder from './NoteElementAdder.vue';

export default {
    name: 'Content',
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder
    },
    data() {
        return {
            toggle: false,
            elementsProper: [],
            elementsDidactic: [],
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
                    view.getElements(view.properStructures, view.elementsProper);
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
                    view.getElements(view.didacticStructures, view.elementsDidactic);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getElements(structuresList, elementsList) {
            structuresList.forEach(function(element, index) {
                axios
                    .get('./api/structures/' + element.id + '/textfields')
                    .then(function(response) {
                        if (response.data.data) {
                            let element = response.data.data[0];
                            element.name = structuresList.find(
                                x => x.id == element.attributes.structures_id
                            ).attributes.name;
                            elementsList.push(element);
                            structuresList[index].add = false;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        }
    }
};
</script>
