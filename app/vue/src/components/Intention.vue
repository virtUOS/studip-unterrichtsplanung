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
                    @removeElement="updateElements"
                    @setDefaultInfo="setInfo"
                />
                <div class="note-element-adder">
                    <button class="add-note" @click="addIndicativeTarget">
                        <span class="add-note-icon"></span>
                        <span class="add-note-text">Richtziel hinzufügen</span>
                    </button>
                </div>
                <Summary :structureName="structureName" :structureId="structures_id"></Summary>
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
            structures_id: 3
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    mounted() {
        this.getIndicativeTargets();
        this.setInfo();
    },
    methods: {
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
        getElementsText() {
            let view = this;
            let text = '';
            this.elements.forEach((element, index) => {
                text = text + '<h3>' + element.name + '</h3>';
                text = text + '<p>' + element.attributes.text + '</p><br>';
            });
            this.structureText = text;
        },
        setInfo() {
            this.$store.state.info = {'id': this.structures_id , 'title': this.structureName};
        }
    }
};
</script>
