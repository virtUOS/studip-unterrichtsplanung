<template>
    <div class="plan-media">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> /
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }} </router-link> / Medien
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <NoteElement
                    :element="element"
                    v-for="element in elements"
                    :key="element.id"
                    @removeElement="updateElements"
                />
                <NoteElementAdder :structures_id="6" :elementList="this.elementList" @addElement="updateElements" />
                <Summary :structureName="structureName" :structureId="6"></Summary>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="6" :title="'Interdependenzen'" />
                <InfoBox :title="structureName" />
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
    name: 'Media',
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
            structureName: 'Medien'
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
            this.elementList = [];
            this.elements = [];
            this.getStructures();
        },
        getStructures() {
            let view = this;
            axios
                .get('./api/structures/6')
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
                    .get('./api/textfields/' + view.plan.id + '/' + element.id)
                    .then(function(response) {
                        if (response.data.data.length > 0) {
                            let element = response.data.data[0];
                            element.name = view.elementList.find(
                                x => x.id == element.attributes.structures_id
                            ).attributes.name;
                            view.elements.push(element);
                            view.elements.sort((a, b) => {
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
        }
    }
};
</script>
