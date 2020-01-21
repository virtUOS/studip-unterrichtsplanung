<template>
    <div class="plan-content">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Inhalt
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div class="plan-content-tabs">
                    <h3 :class="{ active: !toggle }" @click="toggle = false" class="plan-content-tab">{{ technicalStructuresName }}</h3>
                    <h3 :class="{ active: toggle }" @click="toggle = true" class="plan-content-tab">{{ didacticStructuresName }}</h3>
                    <div class="plan-content-analysis plan-content-analysis-technical" v-show="!toggle">
                        <NoteElement
                            :element="elementTechnical"
                            v-for="elementTechnical in elementsTechnical"
                            :key="elementTechnical.id"
                            @removeElement="updateElements"
                            @changeElement="changeElement"
                            @setInfo="setInfo"
                        />
                        <NoteElementAdder
                            :structures_id="technicalStructuresId"
                            :elementList="technicalStructures"
                            @addElement="updateElements"
                        />
                    </div>
                    <div class="plan-content-analysis plan-content-analysis-didactic" v-show="toggle">
                        <NoteElement
                            :element="elementDidactic"
                            v-for="elementDidactic in elementsDidactic"
                            :key="elementDidactic.id"
                            @removeElement="updateElements"
                            @changeElement="changeElement"
                            @setInfo="setInfo"
                        />
                        <NoteElementAdder
                            :structures_id="didacticStructuresId"
                            :elementList="didacticStructures"
                            @addElement="updateElements"
                        />
                    </div>
                    <Summary :structureName="structureName" :structureId="structures_id" :structureText="structureText"></Summary>
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
            elementsTechnical: [],
            elementsDidactic: [],
            contentStructures: [],
            technicalStructures: [],
            didacticStructures: [],
            technicalStructuresId: Number,
            didacticStructuresId: Number,
            technicalStructuresName: '',
            didacticStructuresName: '',
            structureTextTechnical: '',
            structureTextDidactic: '',
            structureText: ''
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    watch: {
        structureTextTechnical() {
            this.structureText = this.structureTextTechnical + this.structureTextDidactic
        },
        structureTextDidactic() {
            this.structureText = this.structureTextTechnical + this.structureTextDidactic
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
            this.getSubstructures();
        },
        changeElement(changedElement) {
            console.log('change element');
            let element = this.elementsDidactic.find( x => x.attributes.id == changedElement.id);
            element.attributes.text = changedElement.text;
            this.getElementsText('technical');
            this.getElementsText('didactic');
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
                        view.technicalStructuresId = parseInt(view.contentStructures[0].id);
                        view.didacticStructuresId = parseInt(view.contentStructures[1].id);
                        view.technicalStructuresName = view.contentStructures[0].name;
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
            this.elementsTechnical = [];
            this.elementsDidactic = [];
            this.technicalStructures = [];
            this.didacticStructures = [];
            //get A
            axios
                .get('./api/structures/' + view.contentStructures[0].id)
                .then(function(response) {
                    view.technicalStructures = response.data.data;
                    view.technicalStructures.forEach(function(element) {
                        element.add = true;
                    });
                    view.getElements(view.technicalStructures, view.elementsTechnical, 'technical');
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
                    view.getElements(view.didacticStructures, view.elementsDidactic, 'didactic');
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getElements(structuresList, elementsList, type) {
            let view = this;
            let promises = [];
            structuresList.forEach(function(element, index) {
                promises.push(axios.get('./api/textfields/' + view.plan.id + '/' + element.id));
            });
            axios.all(promises).then(results => {
                results.forEach(response => {
                    if (response.data.data.length > 0) {
                        let element = response.data.data[0];
                        let listElement = structuresList.find(
                            x => x.id == element.attributes.structures_id
                        );
                        element.name = listElement.attributes.name;
                        listElement.add = false;
                        elementsList.push(element);
                    }
                });
                elementsList.sort((a, b) => {
                    if (a.attributes.id > b.attributes.id) return 1;
                    if (b.attributes.id > a.attributes.id) return -1;
                });
                view.getElementsText(type);
            });
        },
        getElementsText(type) {
            let name = '';
            let elementsList = [];
            switch (type) {
                case 'didactic':
                    name = this.didacticStructuresName;
                    elementsList = this.elementsDidactic;
                    break;
                case 'technical':
                    name = this.technicalStructuresName;
                    //elementsList = this.elementsTechnical;
                    break;
            }
            let view = this;
            let text = '<h3>' + name + '</h3>';
            elementsList.forEach((element, index) => {
                text = text + '<h4>' + element.name + '</h4>';
                text = text + '<p>' + element.attributes.text + '</p><br>';
            });
            if (type == 'didactic') {
                this.structureTextDidactic = text;
            }
            if (type == 'technical') {
                this.structureTextTechnical = text;
            }
        },
        setInfo() {
            this.$store.state.info = {'id': this.structures_id , 'title': this.structureName};
        }
    }
};
</script>
