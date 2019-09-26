<template>
    <div class="overview-box" :class="catName">
        <router-link :to="'/plan/'+planId+'/'+catName"><h1>{{title}}</h1></router-link>
    </div>
</template>

<script>
export default {
    name: 'OverviewBox',
    props: {
        title: String,
        catName: String,
        state: Object,
        planId: Number
    }
}
</script>