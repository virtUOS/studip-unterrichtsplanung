<template>
    <div class="interdepbox-wrapper interdepcomputedbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + structures_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                :class="[
                    { 'interdep-disabled': value == false, 'interdep-pending': value == 'pending' },
                    'interdep-type-' + id
                ]"
                v-for="(value, id) in interdeps_computed"
                :key="id"
            ></button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'InterdepComputedBox',
    props: {
        structures_id: Number,
        title: String
    },

    computed: {
        ...mapGetters(['interdeps', 'plan']),
        interdeps_computed() {
            if (this.interdeps === undefined) {
                return [];
            }

            let interdeps = {};

            Object.keys(this.interdeps[this.structures_id]).forEach(struct_id => {
                // check, if both ideps are true, only one of them or none
                let idep_local  = this.interdeps[this.structures_id][struct_id];
                let idep_remote = this.interdeps[struct_id][this.structures_id];

                if (idep_local && idep_remote) {
                    interdeps[struct_id] = true;
                } else if (idep_local || idep_remote) {
                    interdeps[struct_id] = 'pending';
                } else {
                    interdeps[struct_id] = false;
                }

            });

            return interdeps;

        }
    }
};
</script>
