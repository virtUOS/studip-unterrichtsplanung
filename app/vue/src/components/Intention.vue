<template>
    <div class="plan-intention">
        <h1>
            <router-link to="/">
                <span class="nav home"></span>
            </router-link>/
            <router-link :to="'/plan/' + plan.id">{{ plan.attributes.name }}</router-link>/ Intentionalität
        </h1>
        <div class="content-wrapper">
            <div class="content-container">
                <IndicativeTarget
                    :element="element"
                    v-for="element in elements"
                    :key="element.id"
                    @removeElement="updateElements"
                />
                <Summary :structureName="structureName" :structureId="3"></Summary>
            </div>
            <div class="box-wrapper">
                <InterdepBox :strucutres_id="3" :title="'Interdependenzen'" />
                <InfoBox :title="structureName" />
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
            structureName: 'Intentionalität'
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    mounted() {
        this.getIndicativeTargets();

    },
    methods: {
        updateElements() {
            this.elements = [];
            this.getIndicativeTargets();
        },
        getIndicativeTargets() {
            let view = this;
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
        }
    }
};
</script>
