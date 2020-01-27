<template>
    <div class="infobox-wrapper">
        <h3>{{ structureName }}</h3>
        <div class="infobox-content" v-html="infoText"></div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props:{
        structureId: Number,
        structureName: String
    },
    data() {
        return {
            infoText: ''
        };
    },
    mounted() {
        this.getInfoText();
    },
    watch: {
        structureId() {
            this.getInfoText();
        }
    },
    methods: {
        getInfoText() {
            this.infoText = this.$store.getters.getInfoText(this.structureId);
        }
    }
};
</script>
