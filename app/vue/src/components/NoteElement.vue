<template>
    <div class="note-element">
        <header class="note-element-title">{{ element.name }}</header>
        <textarea class="note-element-content" v-model="element.attributes.text" @blur="autoSave()" />
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'NoteElement',
    props: {
        element: Object
    },
    mounted() {
        // console.log(this.element);
    },
    methods: {
        autoSave: function() {
            let view = this;
            console.log('auto save');
            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.element.attributes.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id
                })
                .then(function(response) {
                    console.log(response);
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
