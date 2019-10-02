<template>
    <div class="note-element">
        <header class="note-element-title">{{ element.name }}</header>
        <textarea class="note-element-content" :value="element.text" @blur="autoSave()" />
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'NoteElement',
    props: {
        element: Object
    },
    mounted() {},
    methods: {
        autoSave: function() {
            console.log('auto save');
            axios
                .put()
                .then()
                .catch();
        }
    }
};
</script>
