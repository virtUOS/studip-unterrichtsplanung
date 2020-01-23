<template>
    <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
        <div class="note-element" :class="{ unfolded: unfolded }">
            <header class="note-element-title">
                <span
                    class="note-element-toggle"
                    @click="toggleElement"
                    :class="{ unfolded: unfolded, folded: !unfolded }"
                >
                    {{ element.name }}
                </span>
                <div class="spinner" v-show="autosave">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>

                <span class="note-element-toolbar">
                    <button @click="copyElement" class="copy" title="Inhalt in die Zwischenablage kopieren"></button>
                    <button @click="removeElement" class="remove" title="Textfeld löschen"></button>
                </span>
            </header>
            <textarea
                ref="noteText"
                class="note-element-content"
                v-model="element.attributes.text"
                @blur="autoSave()"
                @keyup="countChars()"
                @focus="setInfo"
                v-show="unfolded"
            />
        </div>
        <div class="note-element-char-counter" v-show="unfolded" title="Anzahl der Zeichen">{{ charCounter }}</div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'NoteElement',
    props: {
        element: Object
    },
    data() {
        return {
            charCounter: 0,
            unfolded: true,
            autosave: false
        };
    },
    mounted() {
        this.countChars();
    },
    methods: {
        autoSave: function() {
            let view = this;
            this.$emit('setInfo');

            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.element.attributes.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {
                    view.$emit('changeElement', { id: view.element.id, text: view.element.attributes.text });
                    view.autosave = true;
                    setTimeout(function(){ view.autosave = false; }, 2000);
                })
                .catch(error => console.log(error));
        },
        removeElement: function() {
            let view = this;
            console.log('remove element');
            if (!confirm('Möchten Sie das Textfeld ' + view.element.name + ' wirklich löschen?')) {
                return;
            }
            axios
                .delete('./api/textfields/' + view.element.id, {
                    structures_id: view.element.attributes.structures_id,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {
                    view.$emit('removeElement', view.element.id);
                })
                .catch(error => console.log(error));
        },
        countChars() {
            let string = this.$refs.noteText.value;
            //string = string.replace(/\s/g, '');
            string = string.replace(/\n/g, '');
            this.charCounter = string.length;
        },
        toggleElement() {
            this.unfolded = !this.unfolded;
        },
        copyElement() {
            let text = this.element.attributes.text;
            text = this.element.name + '\n' + text;
            navigator.clipboard.writeText(text).then(
                function() {},
                error => {
                    console.log(error);
                }
            );
        },
        setInfo() {
            this.$store.state.info = { id: this.element.attributes.structures_id, title: this.element.name };
        }
    }
};
</script>
