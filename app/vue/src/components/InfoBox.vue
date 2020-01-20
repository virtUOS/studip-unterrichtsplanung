<template>
    <div class="infobox-wrapper">
        <h3>{{ title }}</h3>
        <p>{{ content }}</p>
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
            this.content = 'text';

            // axios
            // .get('.api/infotext/' + structures_id)
            // .then(response => {
            //     view.content = response.data.text;
            // })
            // .catch(error => console.log(error));
        }
    }

};
</script>
