<template>
    <div class="plan-content">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Inhalt
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="plan-content-tabs">
                    <h3 :class="{ active: !toggle }" @click="toggle = false">{{ this.properStructuresName }}</h3>
                    <h3 :class="{ active: toggle }" @click="toggle = true">{{ this.didacticStructuresName }}</h3>
                    <div class="plan-content-analysis plan-content-analysis-proper" v-show="!toggle">
                        <NoteElement
                            :element="elementProper"
                            v-for="elementProper in elementsProper"
                            :key="elementProper.id"
                            @removeElement="updateElements"
                            @setInfo="setInfo"
                        />
                        <NoteElementAdder
                            :structures_id="this.properStructuresId"
                            :elementList="this.properStructures"
                            @addElement="updateElements"
                        />
                    </div>
                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <NoteElement
                            :element="elementDidactic"
                            v-for="elementDidactic in elementsDidactic"
                            :key="elementDidactic.id"
                            @removeElement="updateElements"
                            @setInfo="setInfo"
                        />
                        <NoteElementAdder
                            :structures_id="this.didacticStructuresId"
                            :elementList="this.didacticStructures"
                            @addElement="updateElements"
                        />
                    </div>
                    <Summary :structureName="structureName" :structureId="structures_id"></Summary>
                </div>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="structures_id" :title="'Interdependenzen'" />
                <InfoBox />
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
import Summary from './Summary.vue';

export default {
    name: 'Content',
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder,
        Summary
    },
    data() {
        return {
            toggle: false,
            structureName: 'Inhalt',
            structures_id: 4,
            elementsProper: [],
            elementsDidactic: [],
            contentStructures: [],
            properStructures: [],
            didacticStructures: [],
            properStructuresId: '',
            didacticStructuresId: '',
            properStructuresName: '',
            didacticStructuresName: ''
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    created() {
        this.getContentStructures();
    },
    mounted() {
        this.setInfo();
    },
    methods: {
        updateElements() {
            this.elementsProper = [];
            this.elementsDidactic = [];
            this.contentStructures = [];
            this.properStructures = [];
            this.didacticStructures = [];
            this.getContentStructures();
        },
        getContentStructures() {
            let view = this;
            axios
                .get('./api/structures/4')
                .then(function(response) {
                    if (response.data.data) {
                        response.data.data.forEach(function(struc) {
                            view.contentStructures.push(struc.attributes);
                        });
                        view.properStructuresId = view.contentStructures[0].id;
                        view.didacticStructuresId = view.contentStructures[1].id;
                        view.properStructuresName = view.contentStructures[0].name;
                        view.didacticStructuresName = view.contentStructures[1].name;

                        view.getSubstructures();
                    }
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
            let view = this;
            structuresList.forEach(function(element, index) {
                axios
                    .get('./api/textfields/' + view.plan.id + '/' + element.id)
                    .then(function(response) {
                        if (response.data.data) {
                            let element = response.data.data[0];
                            element.name = structuresList.find(
                                x => x.id == element.attributes.structures_id
                            ).attributes.name;
                            elementsList.push(element);
                            elementsList.sort((a, b) => {
                                if (a.attributes.id > b.attributes.id) return 1;
                                if (b.attributes.id > a.attributes.id) return -1;
                            });
                            structuresList[index].add = false;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        },
        setInfo() {
            this.$store.state.info = {'id': this.structures_id , 'title': this.structureName};
        }
    }
};
</script>
