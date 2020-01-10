<template>
    <div class="plan-situation">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / situative Voraussetzungen
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <NoteElement
                    :element="element"
                    v-for="element in elements"
                    :key="element.id"
                    @removeElement="updateElements"
                />
                <NoteElementAdder :structures_id="1" :elementList="this.elementList" @addElement="updateElements" />
                <Summary :structureName="structureName" :structureId="1"></Summary>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="1" :title="'Interdependenzen'" />
                <InfoBox :title="structureName" />
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

export default {
    name: 'Situation',
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
            structureName: 'situative Voraussetzungen'
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    mounted() {
        this.getStructures();
    },
    methods: {
        updateElements() {
            this.getStructures();
        },
        getStructures() {
            let view = this;
            axios
                .get('./api/structures/1')
                .then(function(response) {
                    let elementList = response.data.data;
                    elementList.forEach(function(element) {
                        element.add = true;
                    });
                    view.elementList = elementList;
                    view.getElements();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getElements() {
            let view = this;
            let elements = [];
            this.elementList.forEach(function(element, index) {
                axios
                    .get('./api/textfields/' + view.plan.id + '/' + element.id)
                    .then(function(response) {
                        if (response.data.data.length > 0) {
                            let element = response.data.data[0];
                            element.name = view.elementList.find(
                                x => x.id == element.attributes.structures_id
                            ).attributes.name;
                            elements.push(element);
                            elements.sort((a, b) => {
                                if (a.attributes.id > b.attributes.id) return 1;
                                if (b.attributes.id > a.attributes.id) return -1;
                            });
                            view.elementList[index].add = false;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
            this.elements = elements;
        }
    }
};
</script>
