<template>
    <router-link :to="'/plan/' + planId + '/' + catName">
        <div class="overview-box" :class="catName">
            <h1>{{ title }}</h1>
            <InterdepBox :strucutres_id="strucutres_id" :buttonsDisable="true" />
        </div>
    </router-link>
</template>

<script>
import InterdepBox from './InterdepBox.vue';
export default {
    name: 'OverviewBox',
    components: {
        InterdepBox
    },
    props: {
        title: String,
        catName: String,
        strucutres_id: Number,
        planId: Number
    }
};
</script>
