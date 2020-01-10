<template>
    <div class="interdepbox-wrapper interdepcomputedbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + strucutres_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                :class="[{ 'interdep-disabled': !value }, 'interdep-type-' + id]"
                v-for="(value, id) in interdeps"
                :key="id"
            ></button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'InterdepComputedBox',
    props: {
        strucutres_id: Number,
        title: String
    },
    data() {
        return { interdeps: {} };
    },
    mounted() {
        this.getInterdeps();
    },
    methods: {
        getInterdeps() {
            let view = this;
            console.log('get interdeps');
            console.log(view.strucutres_id);
            axios
                .get('./api/interdeps/' + view.$store.state.plan.id + '/' + view.strucutres_id)
                .then(function(response) {
                    console.log(response);
                    if (response.data.data) {
                        view.interdeps = JSON.parse(response.data.data[0].attributes.references);
                    } else {
                        console.log(response);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
