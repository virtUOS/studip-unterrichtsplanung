<template>
    <div class="interdepbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + structures_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                v-for="(value, id) in interdeps[structures_id]"
                :key="id"
                :class="[{ 'interdep-disabled': !value }, 'interdep-type-' + id]"
                v-bind:title="getStructureName(id)"
                @click="switchInterdep(id, value)"
            ></button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
    name: 'InterdepBox',
    props: {
        structures_id: Number,
        title: String
    },

    computed: {
        ...mapGetters(['interdeps', 'plan'])
    },

    mounted() {
        this.$store.dispatch('interdeps', this.plan.id);
    },

    methods: {
        switchInterdep(id, value) {
            let view = this;
            this.interdeps[this.structures_id][id] = !value;
            axios
                .put('./api/interdeps/' + view.$store.state.plan.id + '/' + view.structures_id, {
                    references: JSON.stringify(view.interdeps[this.structures_id])
                })
                .then(function() {})
                .catch(error => {
                    console.log(error);
                    view.interdeps[this.structures_id][id] = !value;
                });
        },
        getStructureName(id) {
            switch (id) {
                case '1':
                    return 'Situative Voraussetzungen';
                case '2':
                    return 'Individuelle Voraussetzungen';
                case '3':
                    return 'Intentionalität';
                case '4':
                    return 'Inhalt';
                case '5':
                    return 'Methodik';
                case '6':
                    return 'Medien';
            }
        }
    }
};
</script>
