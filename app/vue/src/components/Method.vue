<template>
    <div class="plan-method">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Methodik
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <div v-if="hasMultipleDidacticElements">
                    <NoteElement
                        v-for="element in elements"
                        :key="element.id"
                        :element="element"
                        :elements="elements"
                        @removeElement="updateElements"
                        @changeElement="changeElement"
                        @resetInfo="resetInfo"
                        @setInfo="setInfo"
                        @sortElements="updateElements"
                    />
                    <NoteElementAdder
                        :structures_id="structureId"
                        :elementList="elementList"
                        :elements="elements"
                        @addElement="updateElements"
                    />
                </div>
                <div v-else>
                    <NoteElement
                        v-if="elementLoaded"
                        :element="element"
                        :elements="[element]"
                        :noRemove="true"
                        @changeElement="changeElement"
                        @resetInfo="resetInfo"
                        @setInfo="setInfo"
                    />
                </div>
                <Summary
                    :structureName="structureName"
                    :structureId="structureId"
                    :structureText="structureText"
                    @summaryEmpty="showEmptyWarning = true"
                    @summaryFilled="showEmptyWarning = false"
                />
            </div>
            <div class="box-wrapper">
                <InterdepBox :structures_id="structureId" :title="'Interdependenzen'" />
                <EmptyWarningBox v-if="showEmptyWarning" />
                <ToolBox />
                <InfoBox :structureId="infoBoxStructureId" :structureName="infoBoxStructureName" />
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
import mixin from './../mixins/mixin.js';
import ToolBox from './ToolBox.vue';
import EmptyWarningBox from './EmptyWarningBox.vue';

export default {
    name: 'Method',
    mixins: [mixin],
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder,
        Summary,
        ToolBox,
        EmptyWarningBox
    },
    data() {
        return {
            // get this from database
            elementList: [],
            elements: [],
            element: {},
            structureName: 'Methodik',
            structureId: 5,
            structureText: '',
            infoBoxStructureId: 5,
            infoBoxStructureName: '',
            elementLoaded: false,
            showEmptyWarning: false,
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
        this.getStructures();
        this.resetInfo();
    },
    methods: {
        updateElements() {
            this.getStructures();
        },
        getStructures() {
            let view = this;
            axios
                .get('./api/structures/' + this.structureId)
                .then(function(response) {
                    view.elementList = response.data.data;
                    view.elementList.forEach(element => {
                        element.add = true;
                        element.attributes.name = view.getStructureName(element);
                    });
                    if(view.hasMultipleDidacticElements) {
                        view.getElements();
                    } else {
                        view.getElement();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        changeElement(changedElement) {
            if (this.hasMultipleDidacticElements) {
                let element = this.elements.find(x => x.attributes.id == changedElement.id);
                element.attributes.text = changedElement.text;
            } else {
                this.element.attributes.text = changedElement.text;
            }

            this.getElementsText();
        },
        getElements() {
            let view = this;
            let promises = [];
            let elements = [];
            this.elementList.forEach(element =>
                promises.push(axios.get('./api/textfields/' + view.plan.id + '/' + element.id))
            );
            axios.all(promises).then(results => {
                results.forEach(response => {
                    if (response.data.data.length > 0) {
                        let element = response.data.data[0];
                        let listElement = view.elementList.find(x => x.id == element.attributes.structures_id);
                        element.name = view.getStructureName(listElement);
                        listElement.add = false;
                        elements.push(element);
                    }
                });
                view.elements = view.sortElements(elements);
                view.getElementsText();
            });
        },
        getElement() {
            let view = this;
            axios
                .get('./api/textfields/' + view.plan.id + '/' + view.structureId)
                .then(response => {
                    if(response.data.data.length > 0) {
                        let element = response.data.data[0];
                        element.name = view.structureName;
                        view.element = element;
                        view.elementLoaded = true;
                        view.getElementsText();
                    } else {
                        view.createElement();
                    }
                })
                .catch(error => console.log(error));
        },
        createElement() {
            let view = this;
            axios
                .post('./api/textfields', {
                    plans_id: view.plan.id,
                    structures_id: view.structureId,
                    text: '',
                    position: 0
                })
                .then(response => {
                    view.getElement();
                })
                .catch(error => {console.log(error)})
        },
        getElementsText() {
            let text = '';
            if(this.hasMultipleDidacticElements) {
                this.elements.forEach(element => {
                    text = text + '<h3>' + element.name + '</h3>';
                    text = text + '<p>' + element.attributes.text + '</p><br>';
                });
            } else {
                text = text + '<h3>' + this.element.name + '</h3>';
                text = text + '<p>' + this.element.attributes.text + '</p><br>';
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
        },
        sortElements(elements) {
            elements.sort((a, b) => {
                if (a.attributes.position > b.attributes.position) return 1;
                if (b.attributes.position > a.attributes.position) return -1;
            });

            return elements;
        }
    }
};
</script>
