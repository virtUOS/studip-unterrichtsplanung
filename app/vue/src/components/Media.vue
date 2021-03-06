<template>
    <div class="plan-media">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / {{structureName}}
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
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
    name: 'Media',
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
            structureId: 6,
            structureText: '',
            infoBoxStructureId: 6,
            infoBoxStructureName: '',
            showEmptyWarning: false,
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        structureName() {
            if (this.plan.attributes.templates_id == 3) {
                return 'Medien, Geräte und Materialien';
            } else {
                return 'Medien';
            }
        },
    },
    mounted() {
        this.getStructures();
        this.resetInfo();
    },
    methods: {
        updateElements() {
            this.getStructures();
        },
        changeElement(changedElement) {
            let element = this.elements.find(x => x.attributes.id == changedElement.id);
            element.attributes.text = changedElement.text;
            this.getElementsText();
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
                    if (view.plan.attributes.templates_id != 3) {
                            view.elementList = view.elementList.filter(elem => {
                                return elem.id != 36;
                            })
                    } else {
                        view.elementList.sort((x,y) => {
                            return x.id == 36 ? -1 : y.id  == 36 ? 1 : 0;
                        });
                    }
                    view.getElements();
                })
                .catch(function(error) {
                    console.log(error);
                });
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
                elements.sort((a, b) => {
                    if (a.attributes.id > b.attributes.id) return 1;
                    if (b.attributes.id > a.attributes.id) return -1;
                });
                view.elements = view.sortElements(elements);
                view.getElementsText();
            });
        },
        getElementsText() {
            let text = '';
            this.elements.forEach(element => {
                text = text + '<h3>' + element.name + '</h3>';
                text = text + '<p>' + element.attributes.text + '</p><br>';
            });
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
