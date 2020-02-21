<template>
    <div class="interdepbox-wrapper interdepcomputedbox-wrapper">
        <h3 v-if="title" :class="['interdep-title-type-' + structures_id]">{{ title }}</h3>
        <div class="interdeps">
            <button
                :class="[
                    { 'interdep-disabled': value == false, 'interdep-pending': value == 'pending' },
                    'interdep-type-' + id
                ]"
                v-for="(value, id) in interdeps[structures_id]"
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
        ...mapGetters(['interdeps', 'plan'])
    }
};
</script>
