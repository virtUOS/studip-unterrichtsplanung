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
                <spinner :show="showSpinner" @done="showSpinner = false"/>
                <span class="note-element-toolbar">
                    <button v-if="!isLastElement" @click="lowerElement" class="lower" title="Element nach unten verschieben"></button>
                    <button v-if="!isFirstElement" @click="raiseElement" class="raise" title="Element nach oben verschieben"></button>
                    <button @click="copyElement" class="copy" title="Inhalt in die Zwischenablage kopieren"></button>
                    <button v-if="!noRemove" @click="removeElement" class="remove" title="Textfeld löschen"></button>
                </span>
            </header>
            <textarea
                ref="noteText"
                class="note-element-content"
                v-model="element.attributes.text"
                @blur="autoSave()"
                @keyup="countChars()"
                @focus="$emit('setInfo', {id: element.attributes.structures_id, name: element.name})"
                v-show="unfolded"
            />
        </div>
        <div class="note-element-char-counter" v-show="unfolded" title="Anzahl der Zeichen">{{ charCounter }}</div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './Spinner.vue';

export default {
    name: 'NoteElement',
    components: {
        Spinner
    },
    props: {
        element: Object,
        elements: Array,
        noRemove: Boolean
    },
    data() {
        return {
            charCounter: 0,
            unfolded: true,
            showSpinner: false
        };
    },
    mounted() {
        this.countChars();
    },
    computed: {
        isFirstElement() {
            if (this.elements[0].id == this.element.id) {return true;} else {return false;}
        },
        isLastElement() {
            let last = this.elements.length-1;
            if (this.elements[last].id == this.element.id) {return true;} else {return false;}
        }
    },
    methods: {
        autoSave: function() {
            let view = this;
            this.$emit('resetInfo');

            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.element.attributes.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {
                    view.$emit('changeElement', { id: view.element.id, text: view.element.attributes.text });
                    view.showSpinner = true;
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
        lowerElement() {
            let view = this;
            let textfields = [];
            let currentPosition = parseInt(this.element.attributes.position);
            let elementBelow = this.elements.find(item => item.attributes.position == currentPosition + 1);
            if (elementBelow == undefined) {
                let newPos = 0;
                this.elements.forEach((item, index) => {
                    let elem = {};
                    elem.id = item.id;
                    if(item.id == view.element.id) {
                        elem.position = index + 1;
                        newPos = index + 1;
                    } else {
                        if (index == newPos) {
                            elem.position = index - 1;
                        } else {
                            elem.position = index;
                        }
                    }
                    textfields.push(elem);
                });
            } else {
                textfields.push({id: view.element.id, position: parseInt(this.element.attributes.position) + 1});
                textfields.push({id: elementBelow.id, position: currentPosition});
            }

            axios
                .put('./api/textfields_position', {
                        textfields: textfields,
                        plans_id:  this.$store.state.plan.id
                    })
                    .then(function() {
                        view.$emit('sortElements');
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

        },
        raiseElement() {
            let view = this;
            let textfields = [];
            let currentPosition = parseInt(this.element.attributes.position);
            let elementAbove = this.elements.find(item => item.attributes.position == currentPosition - 1);

            if (elementAbove == undefined) {
                let currentPos = 0;
                this.elements.forEach((item, index) => {
                    view.elements[index].position = index;
                    if (item.id == view.element.id) {
                        currentPos = index;
                    }
                });
                this.elements.forEach((item, index) => {
                    let elem = {};
                    elem.id = item.id;
                    elem.position = index;
                    if (index == currentPos) {
                        elem.position = index - 1;
                    }
                    if (index == currentPos -1) {
                        elem.position = currentPos;
                    }
                    textfields.push(elem);
                });
            } else {
                textfields.push({id: view.element.id, position: parseInt(this.element.attributes.position) - 1});
                textfields.push({id: elementAbove.id, position: currentPosition});
            }

            axios
                .put('./api/textfields_position', {
                        textfields: textfields,
                        plans_id:  this.$store.state.plan.id
                    })
                    .then(function() {
                        view.$emit('sortElements');
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        },
    }
};
</script>
