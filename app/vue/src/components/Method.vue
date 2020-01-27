<template>
    <div class="plan-method">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Methodik
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <NoteElement
                    :element="element"
                    v-for="element in elements"
                    :key="element.id"
                    @removeElement="updateElements"
                    @changeElement="changeElement"
                    @resetInfo="resetInfo"
                    @setInfo="setInfo"
                />
                <NoteElementAdder
                    :structures_id="structureId"
                    :elementList="elementList"
                    @addElement="updateElements"
                />
                <Summary
                    :structureName="structureName"
                    :structureId="structureId"
                    :structureText="structureText"
                ></Summary>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="structureId" :title="'Interdependenzen'" />
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

export default {
    name: 'Method',
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder,
        Summary
    },
    data() {
        return {
            // get this from database
            elementList: [],
            elements: [],
            structureName: 'Methodik',
            structureId: 5,
            structureText: '',
            infoBoxStructureId: 5,
            infoBoxStructureName: '',
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
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
                    view.elementList.forEach(function(element) {
                        element.add = true;
                    });
                    view.getElements();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        changeElement(changedElement) {
            let element = this.elements.find(x => x.attributes.id == changedElement.id);
            element.attributes.text = changedElement.text;
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
                        element.name = listElement.attributes.name;
                        listElement.add = false;
                        elements.push(element);
                    }
                });
                elements.sort((a, b) => {
                    if (a.attributes.id > b.attributes.id) return 1;
                    if (b.attributes.id > a.attributes.id) return -1;
                });
                view.elements = elements;
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
        }
    }
};
</script>
