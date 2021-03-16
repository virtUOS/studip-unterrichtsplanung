<template>
    <div class="plan-content">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Inhalt
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="plan-content-tabs">
                    <h3 :class="{ active: !toggle }" @click="toggle = false; setInfo({id: structureIdTechnical, name: technicalStructureName});" class="plan-content-tab">
                            {{ technicalStructureName }}
                    </h3>
                    <h3 :class="{ active: toggle }" @click="toggle = true; setInfo({id: structureIdDidactic, name: didacticStructureName});" class="plan-content-tab">
                            {{ didacticStructureName }}
                    </h3>
                    <div class="plan-content-analysis plan-content-analysis-technical" v-show="!toggle">
                        <NoteElement
                            v-if="technicalElementLoaded"
                            :element="technicalElement"
                            :elements="[technicalElement]"
                            :noRemove="true"
                            @changeElement="changeTechnicalElement"
                            @resetInfo="resetInfo"
                            @setInfo="setInfo"
                        />
                    </div>

                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <div v-if="hasMultipleDidacticElements">
                            <NoteElement
                                v-for="element in didacticElements"
                                :key="element.id"
                                :element="element"
                                :elements="didacticElements"
                                @removeElement="updateDidacticElements"
                                @changeElement="changeDidacticElement"
                                @resetInfo="resetInfo"
                                @setInfo="setInfo"
                                @sortElements="updateDidacticElements"
                            />
                            <NoteElementAdder
                                v-if="didacticInfoLoaded"
                                :structures_id="structureIdDidactic"
                                :elementList="didacticElementList"
                                :elements="didacticElements"
                                @addElement="updateDidacticElements"
                            />
                        </div>
                        <div v-else>
                            <NoteElement
                                v-if="didacticElementLoaded"
                                :element="didacticElement"
                                :elements="[didacticElement]"
                                :noRemove="true"
                                @changeElement="changeDidacticElement"
                                @resetInfo="resetInfo"
                                @setInfo="setInfo"
                            />
                        </div>
                    </div>
                    <Summary
                        :structureName="structureName"
                        :structureId="structureId"
                        :structureText="structureText"
                    ></Summary>
                </div>
            </div>
            <div class="box-wrapper">
                <InterdepBox :structures_id="structureId" :title="'Interdependenzen'" />
                <ToolBox />
                <InfoBox :structureId="infoBoxStructureId" :structureName="infoBoxStructureName" />
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import InterdepBox from './InterdepBox.vue';
import InfoBox from './InfoBox.vue';
import NoteElement from './NoteElement.vue';
import NoteElementAdder from './NoteElementAdder.vue';
import Summary from './Summary.vue';
import mixin from './../mixins/mixin.js';
import ToolBox from './ToolBox.vue';

export default {
    name: 'Content',
    mixins: [mixin],
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder,
        Summary,
        ToolBox
    },
    data() {
        return {
            // get this from database
            didacticElementList: [],
            didacticElements: [],
            didacticElement: {},
            technicalElement: {},
            structureName: 'Inhalt',
            didacticStructureName: '',
            technicalStructureName: '',
            structureId: 4,
            structureIdDidactic: '',
            structureIdTechnical: '',
            structureText: '',
            infoBoxStructureId: 4,
            infoBoxStructureName: '',

            contentStructures: [],
            toggle: false,
            didacticInfoLoaded: false,
            technicalElementLoaded: false,
            didacticElementLoaded: false,
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        hasMultipleDidacticElements() {
            let template = this.plan.attributes.templates_id;
            if (template == 2) { // Mathematik
                return false;
            } else {
                return true;
            }
        }
    },
    mounted() {
        this.getContentStructures();
        this.resetInfo();
    },
    methods: {
        updateDidacticElements() {
            this.getDidacticStructures();
        },
        changeDidacticElement(changedElement) {
            let element = this.didacticElements.find(x => x.attributes.id == changedElement.id);
            element.attributes.text = changedElement.text;
            this.getElementsText();
        },
        changeTechnicalElement(changedElement)  {
            this.technicalElement.attributes.text = changedElement.text;
            this.getElementsText();
        },
        getContentStructures() {
            let view = this;

            axios
                .get('./api/structures/' +  view.structureId)
                .then(function(response) {
                    if (response.data.data) {
                        response.data.data.forEach(function(struc) {
                            view.contentStructures.push(struc.attributes);
                        });
                        view.structureIdTechnical = parseInt(view.contentStructures[0].id);
                        view.technicalStructureName = view.contentStructures[0].name;
                        view.getTechnicalElement();

                        view.structureIdDidactic = parseInt(view.contentStructures[1].id);
                        view.didacticStructureName = view.contentStructures[1].name;
                        view.didacticInfoLoaded = true;
                        if(view.hasMultipleDidacticElements) {
                            view.getDidacticStructures();
                        } else {
                            view.getDidacticElement();
                        }
                        
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getDidacticStructures() {
            let view = this;
            axios
                .get('./api/structures/' + this.structureIdDidactic)
                .then(function(response) {
                    let elementList = response.data.data;
                    elementList.forEach(function(element) {
                        element.add = true;
                    });
                    view.didacticElementList = elementList;
                    view.getDidacticElements();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getDidacticElements() {
            let view = this;
            let promises = [];
            let elements = [];
            this.didacticElementList.forEach(element =>
                promises.push(axios.get('./api/textfields/' + view.plan.id + '/' + element.id))
            );
            axios.all(promises).then(results => {
                results.forEach(response => {
                    if (response.data.data.length > 0) {
                        let element = response.data.data[0];
                        let listElement = view.didacticElementList.find(x => x.id == element.attributes.structures_id);
                        element.name = listElement.attributes.name;
                        listElement.add = false;
                        elements.push(element);
                    }
                });

                view.didacticElements = view.sortDidacticElements(elements);
                view.getElementsText();
            });
        },
        getDidacticElement() {
            let view = this;

            axios
                .get('./api/textfields/' + view.plan.id + '/' + view.structureIdDidactic)
                .then(response => {
                    if(response.data.data.length > 0) {
                        let element = response.data.data[0];
                        element.name = view.didacticStructureName;
                        view.didacticElement = element;
                        view.didacticElementLoaded = true;
                        view.getElementsText();
                    } else {
                        view.createDidactical();
                    }
                })
                .catch(error => console.log(error));
        },
        createDidactical() {
            let view = this;

            axios
                .post('./api/textfields', {
                    plans_id: view.plan.id,
                    structures_id: view.structureIdDidactic,
                    text: '',
                    position: 0
                })
                .then(response => {
                    view.getDidacticElement();
                })
                .catch(error => {console.log(error)})
        },
        sortDidacticElements(elements) {
            elements.sort((a, b) => {
                if (a.attributes.position > b.attributes.position) return 1;
                if (b.attributes.position > a.attributes.position) return -1;
            });

            return elements;
        },
        getTechnicalElement() {
            let view = this;

            axios
                .get('./api/textfields/' + view.plan.id + '/' + view.structureIdTechnical)
                .then(response => {
                    if(response.data.data.length > 0) {
                        let element = response.data.data[0];
                        element.name = view.technicalStructureName;
                        view.technicalElement = element;
                        view.technicalElementLoaded = true;
                        view.getElementsText();
                    } else {
                        view.createTechnical();
                    }
                })
                .catch(error => console.log(error));
        },
        createTechnical() {
            let view = this;

            axios
                .post('./api/textfields', {
                    plans_id: view.plan.id,
                    structures_id: view.structureIdTechnical,
                    text: '',
                    position: 0
                })
                .then(response => {
                    view.getTechnicalElement();
                })
                .catch(error => {console.log(error)})
        },
        getElementsText() {
            let text = '';
            if(this.technicalElement.attributes) {
                text = text + '<h3>' + this.technicalElement.name + '</h3>';
                text = text + '<p>' + this.technicalElement.attributes.text + '</p><br>';
            }
            if (this.hasMultipleDidacticElements) {
                text = text + '<h3>' +  this.didacticStructureName + '</h3>';

                this.didacticElements.forEach(element => {
                    text = text + '<h4>' + element.name + '</h4>';
                    text = text + '<p>' + element.attributes.text + '</p><br>';
                });
            } else {
                text = text + '<h3>' + this.didacticElement.name + '</h3>';
                text = text + '<p>' + this.didacticElement.attributes.text + '</p><br>';
            }


            this.structureText = text;
        },
        resetInfo() {
            this.infoBoxStructureId = this.structureId;
            this.infoBoxStructureName = this.structureName;
        },
        setInfo(data) {
            this.infoBoxStructureId = parseInt(data.id);
            this.infoBoxStructureName = data.name;
        }
    }
};
</script>
