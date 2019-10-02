<template>
    <div class="plan-situation">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.name }} </router-link> / situative Voraussetzungen
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <!--- create component for each note extract from plan--->
                <NoteElement :element="element" v-for="element in elements" :key="element.id" />
                <NoteElementAdder :structures_id="1" :elementList="this.elementList" @addElement="addElement" />
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
// import axios from 'axios';
import InfoBox from './InfoBox.vue';
import NoteElement from './NoteElement.vue';
import NoteElementAdder from './NoteElementAdder.vue';

export default {
    name: 'Situation',
    components: {
        InfoBox,
        NoteElement,
        NoteElementAdder
    },
    data() {
        return {
            // get this from database
            elementList: [
                { id: 1, name: 'Klassenkomposition', add: true },
                { id: 2, name: 'Soziale Situation in der Schulklasse', add: true },
                { id: 3, name: 'Räumliche und zeitliche Voraussetzungen', add: true },
                { id: 4, name: 'Situation der Schule', add: true },
                { id: 5, name: 'Lehrplanvorgaben', add: false }
            ],
            elements: [{ id: 5, name: 'Lehrplanvorgaben', text: 'lorem ipsum' }]
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        infoBoxTitle() {
            return 'situative Voraussetzungen';
        }
    },
    methods: {
        addElement(element_id) {
            this.elements.push({
                id: element_id,
                name: this.elementList.find(x => x.id == element_id).name,
                text: 'it works'
            });
            // update element list
        }
    }
};
</script>
