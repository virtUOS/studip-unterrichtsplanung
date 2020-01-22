<template>
    <div class="infobox-wrapper">
        <h3>{{ title }}</h3>
        <div class="infobox-content" v-html="content"></div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            title: '',
            content: ''
        }
    },
    computed: {
        info() { return this.$store.state.info;}
    },
    mounted() {
    },
    watch: {
        info: function(obj) {
            this.getInfo();
        }
    },
    methods: {
        getInfo() {
            let view = this;
            let structures_id = this.info.id;
            this.title = this.info.title;

            axios
            .get('./api/infotexts/' + structures_id)
            .then(response => {
                if(response.data.data.length > 0) {
                    view.content = response.data.data[0].attributes.text;
                } else {
                    view.content = '<p class="infobox-nodata">Informationen konnten nicht geladen werden.</p>';
                }
            })
            .catch(error => console.log(error));
        }
    }

};
</script>
