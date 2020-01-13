<template>
    <div class="toolbox-wrapper">
        <h3>Aktionen</h3>
        <div class="tools">
            <router-link :to="'/editplan/' + planId"
                ><span class="toolbox-edit" title="Plan bearbeiten"></span
            ></router-link>
            <router-link :to="'/editschedule/' + planId"
                ><span class="toolbox-schedule" title="Verlaufsplan bearbeiten"></span
            ></router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ToolBox',
    props: {
        planId: String
    },
    methods: {}
};
</script>

<style></style>
