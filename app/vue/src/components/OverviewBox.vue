<template>
    <router-link :to="'/plan/'+planId+'/'+catName">
        <div class="overview-box" :class="catName">
            <h1>{{title}}</h1>
        </div>
    </router-link>
</template>

<script>
export default {
    name: 'OverviewBox',
    props: {
        title: String,
        catName: String,
        interdep: Object,
        planId: Number
    }
}
</script>