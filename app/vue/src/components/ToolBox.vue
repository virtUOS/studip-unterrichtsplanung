<template>
    <div class="toolbox-wrapper">
        <h3>Aktionen</h3>
        <div class="tools">
            <router-link :to="'/editplan/' + plan.id">
                <span class="toolbox-edit" title="Plan bearbeiten"></span>
            </router-link>
            <router-link :to="'/editschedule/' + plan.id">
                <span class="toolbox-schedule" title="Verlaufsplan bearbeiten"></span>
            </router-link>
            <a :href="'api/export/pdf/' + this.plan.id" target="_blank"><span class="toolbox-pdf" :title="this.plan.attributes.name + ' als PDF exportieren'"></span></a>
            <a :href="'api/export/doc/' + this.plan.id" target="_blank"><span class="toolbox-doc" :title="this.plan.attributes.name + ' als DOC exportieren'"></span></a>
            <a :href="'api/export/plain/' + this.plan.id" target="_blank"><span class="toolbox-plain" :title="this.plan.attributes.name + ' als unformatierten Text exportieren'"></span></a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ToolBox',
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    methods: {}
};
</script>

<style></style>
