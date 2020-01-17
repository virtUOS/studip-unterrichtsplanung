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
            unfolded: true
        };
    },
    mounted() {
        // console.log(this.element);
        this.countChars();
    },
    methods: {
        autoSave: function() {
            let view = this;
            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.element.attributes.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {})
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
            string = string.replace(/\s/g, '');
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
        }
    }
};
</script>
