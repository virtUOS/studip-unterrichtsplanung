<template>
    <div class="interdepbox-wrapper interdepcomputedbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + strucutres_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                :class="[
                    { 'interdep-disabled': value == false, 'interdep-pending': value == 'pending' },
                    'interdep-type-' + id
                ]"
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
        return {
            interdeps: {},
            remoteInterdeps: {}
        };
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
                        view.getRemoteInterdeps();
                    } else {
                        view.createInterdep();
                    }
                })
                .catch(function(error) {
                    console.log(error);
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
        },
        getRemoteInterdeps() {
            let view = this;
            let promises = [];
            let remoteInterdeps = { 1: false, 2: false, 3: false, 4: false, 5: false, 6: false };
            delete remoteInterdeps[this.strucutres_id];
            Object.keys(remoteInterdeps).forEach(interdep =>
                promises.push(axios.get('./api/interdeps/' + view.$store.state.plan.id + '/' + interdep))
            );
            axios.all(promises).then(results => {
                results.forEach(response => {
                    if (response.data.data) {
                        let id = response.data.data[0].id[1];
                        let i = JSON.parse(response.data.data[0].attributes.references);
                        if (i[view.strucutres_id]) {
                            remoteInterdeps[id] = true;
                        }
                    }
                });
                view.remoteInterdeps = remoteInterdeps;
                view.setColors();
            });
        },
        setColors() {
            let view = this;
            Object.keys(this.interdeps).forEach(x => {
                let interdep = view.interdeps[x];
                let remoteInterdep = view.remoteInterdeps[x];
                if (interdep && remoteInterdep) {
                    view.interdeps[x] = true;
                }
                // if ((!interdep && remoteInterdep) || (interdep && !remoteInterdep)) {
                if (interdep && !remoteInterdep) {
                    view.interdeps[x] = 'pending';
                }
                if (!interdep && !remoteInterdep) {
                    view.interdeps[x] = false;
                }
            });
        }
    }
};
</script>
