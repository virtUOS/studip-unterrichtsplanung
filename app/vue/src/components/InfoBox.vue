<template>
    <div class="infobox-wrapper" v-if="infoText">
        <h3 class="header" >{{ structureName }}</h3>
        <div class="infobox-content">
            <p v-html="infoText"></p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex';

export default {
    props:{
        structureId: Number,
        structureName: String
    },
    computed: {
        ...mapGetters(['infos']),
        infoText() {
            let info = this.infos.find(x => x.attributes.structures_id == this.structureId);
            if ((info != undefined) && (info.attributes.text != '')) {
                return info.attributes.text;
            } else {
                return false;
            }
        }
    }
};
</script>
