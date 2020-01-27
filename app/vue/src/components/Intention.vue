<template>
    <div class="plan-intention">
        <h1>
            <router-link to="/"> <span class="nav home"></span> </router-link>/
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }}</router-link
            >/ Intentionalität
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <IndicativeTarget
                    :element="element"
                    v-for="element in elements"
                    :key="element.id"
                    @removeElement="removeIndicativeElement"
                    @setDefaultInfo="resetInfo"
                    @setInfo="setInfo"
                    @structureText="setIndicativeStructureTexts"
                />
                <div class="note-element-adder">
                    <button class="add-note" @click="addIndicativeTarget">
                        <span class="add-note-icon"></span>
                        <span class="add-note-text">Richtziel hinzufügen</span>
                    </button>
                </div>
                <Summary :structureName="structureName" :structureId="structureId" :structureText="structureText" />
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
import IndicativeTarget from './IndicativeTarget';
import Summary from './Summary';

export default {
    name: 'Intention',
    components: {
        InfoBox,
        InterdepBox,
        IndicativeTarget,
        Summary
    },
    data() {
        return {
            // get this from database
            elements: [],
            structureName: 'Intentionalität',
            structureId: 3,
            structureText: '',
            indicativeStructureTexts: [],
            infoBoxStructureId: this.structureId,
            infoBoxStructureName: this.structureName
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    watch: {
        indicativeStructureTexts: {
            handler: function() {
                this.getElementsText();
            },
            deep: true
        }
    },
    mounted() {
        this.getIndicativeTargets();
        this.resetInfo();
        this.getElementsText();
    },
    methods: {
        removeIndicativeElement(elementId) {
            this.indicativeStructureTexts = this.indicativeStructureTexts.filter(x => {
                return x.id !== elementId;
            });
            this.updateElements();
        },
        updateElements() {
            this.getIndicativeTargets();
        },
        getIndicativeTargets() {
            let view = this;
            view.elements = [];
            axios
                .get('./api/textfields/' + view.plan.id + '/17')
                .then(function(response) {
                    if (response.data.data.length > 0) {
                        let elements = response.data;
                        elements.data.forEach(element => {
                            view.elements.push(element);
                        });
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        addIndicativeTarget() {
            let view = this;
            axios
                .post('./api/textfields', {
                    structures_id: 17,
                    text: '',
                    plans_id: view.$store.state.plan.id
                })
                .then(function() {
                    view.getIndicativeTargets();
                })
                .catch(error => console.log(error));
        },
        setIndicativeStructureTexts(indicativeText) {
            let textObj = this.indicativeStructureTexts.find(x => x.id == indicativeText.id);
            let foundText = textObj != undefined;
            if (foundText) {
                textObj.text = indicativeText.text;
            } else {
                this.indicativeStructureTexts.push(indicativeText);
                this.indicativeStructureTexts.sort((a, b) => {
                    if (a.id > b.id) return 1;
                    if (b.id > a.id) return -1;
                });
            }
        },
        getElementsText() {
            let view = this;
            let text = '';
            text = '<h2>' + this.structureName + '</h2>';
            this.elements.forEach(
                element => (text = text + view.indicativeStructureTexts.find(x => x.id == element.id).text)
            );
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
