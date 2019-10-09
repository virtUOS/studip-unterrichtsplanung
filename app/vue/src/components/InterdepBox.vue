<template>
    <div class="interdepbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + strucutres_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                :class="[{ 'interdep-disabled': !value }, 'interdep-type-' + id]"
                @click="switchInterdep(id, value)"
                v-for="(value, id) in interdeps"
                :key="id"
            ></button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'InterdepBox',
    props: {
        strucutres_id: Number,
        title: String,
        buttonsDisable: Boolean
    },
    data() {
        return { interdeps: { '2': false, '3': true, '4': false, '5': true, '6': false } };
    },
    methods: {
        getInterdeps() {
            let view = this;
            axios
                .get('./api/structures/' + view.strucutres_id + '/interdeps')
                .then(function(response) {
                    console.log(response.data.data.references);
                    view.interdeps = response.data.data.references;
                })
                .catch(function(error) {});
        },
        switchInterdep(id, value) {
            if (this.buttonsDisable) {
                return;
            }
            this.interdeps[id] = !value;
        }
    }
};
</script>
