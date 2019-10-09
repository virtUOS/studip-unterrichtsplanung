<template>
    <div class="plan-method">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Methodik
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <NoteElement :element="element" v-for="element in elements" :key="element.id" />
                <NoteElementAdder :structures_id="5" :elementList="this.elementList" @addElement="addElement" />
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="5" :title="'Interdependenzen'" />
                <InfoBox :title="infoBoxTitle" />
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

export default {
    name: 'Method',
    components: {
        InfoBox,
        InterdepBox,
        NoteElement,
        NoteElementAdder
    },
    data() {
        return {
            // get this from database
            elementList: [],
            elements: []
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        infoBoxTitle() {
            return 'Methodik';
        }
    },
    mounted() {
        this.getStructures();
    },
    methods: {
        addElement() {
            this.elementList = [];
            this.elements = [];
            this.getStructures();
        },
        getStructures() {
            let view = this;
            axios
                .get('./api/structures/5')
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
        getElements() {
            let view = this;
            this.elementList.forEach(function(element, index) {
                axios
                    .get('./api/structures/' + element.id + '/textfields')
                    .then(function(response) {
                        if (response.data.data) {
                            let element = response.data.data[0];
                            element.name = view.elementList.find(
                                x => x.id == element.attributes.structures_id
                            ).attributes.name;
                            view.elements.push(element);
                            view.elementList[index].add = false;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        }
    }
};
</script>
