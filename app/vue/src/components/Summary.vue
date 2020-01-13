<template>
    <div class="note-element-wrapper">
        <div class="note-element">
            <header class="note-element-title">Zusammenfassung für {{ structureName }}</header>
            <textarea ref="summaryText" class="note-element-content" :value="summaryElement.text" />
        </div>
        <div class="note-element-char-counter" title="Anzahl der Zeichen">{{ charCounter }}</div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Summary',
    props: {
        structureName: String,
        structureId: Number
    },
    data() {
        return {
            wysiwyg: Object,
            charCounter: 0,
            summaryElement: Object
        };
    },

    mounted() {
        // console.log(this.element);
        let view = this;
        this.getSummary();

        STUDIP.wysiwyg.replace(this.$refs.summaryText);

        var wysiwyg_editor = CKEDITOR.instances[this.$refs.summaryText.id];
        wysiwyg_editor.on('blur', function() {
            view.autoSave(wysiwyg_editor.getData());
        });
        wysiwyg_editor.on('change', function() {
            view.countChars();
        });
    },
    methods: {
        getSummary: function() {
            let view = this;
            axios
                .get('./api/summary/' + view.$store.state.plan.id + '/' + view.structureId)
                .then(response => {
                    if (response.data.data.length > 0) {
                        view.summaryElement = response.data.data[0].attributes;
                        view.charCounter = view.summaryElement.text.replace(/\s/g, '').length;
                    } else {
                        view.createSummary();
                    }
                })
                .catch(error => error);
        },
        createSummary: function() {
            let view = this;
            axios
                .post('./api/summary', {
                    structures_id: view.structureId,
                    text: '',
                    plans_id: view.$store.state.plan.id
                })
                .then(function() {})
                .catch(error => {
                    console.log(error);
                });
        },
        autoSave: function(text) {
            let view = this;
            axios
                .put('./api/summary/' + view.summaryElement.id, {
                    structures_id: view.structureId,
                    text: text,
                    plans_id: view.$store.state.plan.id
                })
                .then(function() {})
                .catch(error => {
                    console.log(error);
                });
        },
        countChars() {
            var wysiwyg_editor = CKEDITOR.instances[this.$refs.summaryText.id];
            let string = wysiwyg_editor.getData();
            string = string.replace(/\s/g, '');
            this.charCounter = string.length;
        }
    }
};
</script>