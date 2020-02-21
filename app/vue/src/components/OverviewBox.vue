<template>
    <router-link :to="'/plan/' + planId + '/' + catName" class="overview-box-link">
        <div class="overview-box" :class="catName">
            <h1>{{ title }}</h1>
            <InterdepComputedBox :structures_id="structures_id" />
        </div>
    </router-link>
</template>

<script>
import InterdepComputedBox from './InterdepComputedBox.vue';
export default {
    name: 'OverviewBox',
    components: {
        InterdepComputedBox
    },
    props: {
        title: String,
        catName: String,
        structures_id: Number,
        planId: String
    }
};
</script>
