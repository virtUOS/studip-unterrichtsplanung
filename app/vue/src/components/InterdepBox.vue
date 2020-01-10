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
            axios
                .get('./api/interdeps/' + view.$store.state.plan.id + '/' + view.strucutres_id)
                .then(function(response) {
                    if (response.data.data) {
                        view.interdeps = JSON.parse(response.data.data[0].attributes.references);
                    } else {
                        view.createInterdep();
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        switchInterdep(id, value) {
            let view = this;
            this.interdeps[id] = !value;
            axios
                .put('./api/interdeps/' + view.$store.state.plan.id + '/' + view.strucutres_id, {
                    references: JSON.stringify(view.interdeps)
                })
                .then(response => console.log(response))
                .catch(error => {
                    console.log(error);
                    view.interdeps[id] = !value;
                });
        },
        createInterdep() {
            let view = this;
            let interdeps = { '1': false, '2': false, '3': false, '4': false, '5': false, '6': false };
            delete interdeps[this.strucutres_id];
            interdeps = JSON.stringify(interdeps);

            axios
                .post('./api/interdeps/' + view.$store.state.plan.id + '/' + view.strucutres_id, {
                    references: interdeps
                })
                .then(response => {
                    console.log(response);
                    view.getInterdeps();
                })
                .catch(error => console.log(error));
        }
    }
};
</script>
