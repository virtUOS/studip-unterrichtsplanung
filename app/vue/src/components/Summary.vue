<template>
    <div class="note-element-wrapper">
        <div class="note-element">
            <header class="note-element-title summary-title">
                <span>Zusammenfassung für {{ structureName }}</span>
                <spinner :show="showSpinner" @done="showSpinner = false"/>
                <button
                    class="summary-copy-all"
                    @click="insertFromTextfields"
                    title="Mit Inhalten aus Textfeldern befüllen"
                ></button>
            </header>
            <textarea ref="summaryText" class="note-element-content" :value="summaryElement.text" />
        </div>
        <div class="note-element-char-counter" title="Anzahl der Zeichen">{{ charCounter }}</div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './Spinner.vue';

export default {
    name: 'Summary',
    components: {
        Spinner
    },
    props: {
        structureName: String,
        structureId: Number,
        structureText: String
    },
    data() {
        return {
            wysiwyg: Object,
            charCounter: 0,
            summaryElement: Object,
            showSpinner: false
        };
    },

    mounted() {
        this.getSummary();
    },
    methods: {
        initCKE() {
            let view = this;
            const textarea = this.$refs.summaryText;
            STUDIP.wysiwyg.replace(textarea);
            let wysiwyg_editor = STUDIP.wysiwyg.getEditor(textarea);
            wysiwyg_editor.ui.focusTracker.on('change:isFocused', (evt, name, isFocused) => {
                if ( !isFocused ) {
                    view.autoSave(wysiwyg_editor.getData());
                }
            });
            wysiwyg_editor.model.document.on('change:data', () => {
                view.countChars();
            });
        },
        getSummary: function() {
            let view = this;
            axios
                .get('./api/summary/' + view.$store.state.plan.id + '/' + view.structureId)
                .then(response => {
                    if (response.data.data.length > 0) {
                        view.summaryElement = response.data.data[0].attributes;
                        view.charCounter = view.summaryElement.text.replace(/<[^>]*>/g, '').replace(/\r?\n|\r/g, '').replace(/&nbsp;/g, '').length;
                        view.initCKE();
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
                .then(response => {
                    view.summaryElement = response.data;
                    view.initCKE();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        autoSave: function(text) {
            let view = this;
            this.showSpinner = true;
            axios
                .put('./api/summary/' + view.summaryElement.id, {
                    structures_id: view.structureId,
                    text: text,
                    plans_id: view.$store.state.plan.id
                })
                .then(response => {
                    view.summaryElement = response.data;
                    view.showSpinner = false;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        countChars() {
            const textarea = this.$refs.summaryText;
            let wysiwyg_editor = STUDIP.wysiwyg.getEditor(textarea);
            let string = wysiwyg_editor.getData();
            string = string.replace(/<[^>]*>/g, '').replace(/\r?\n|\r/g, '').replace(/&nbsp;/g, '');
            this.charCounter = string.length;
        },
        insertFromTextfields() {
            if (
                confirm(
                    'Möchten Sie den Inhalt aus allen Textfeldern einfügen? Der aktuelle Inhalt wird überschrieben!'
                )
            ) {
                const textarea = this.$refs.summaryText;
                let wysiwyg_editor = STUDIP.wysiwyg.getEditor(textarea);
                wysiwyg_editor.setData(this.structureText);
                this.autoSave(wysiwyg_editor.getData());
            }
        }
    },
    watch: {
        summaryElement(newVal, oldVal) {
            if (newVal.text && newVal.text !== '') {
                this.$emit('summaryFilled');
            } else {
                this.$emit('summaryEmpty');
            }
        }
    }
};
</script>
