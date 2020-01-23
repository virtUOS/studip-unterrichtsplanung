<template>
    <div class="target-wrapper indicative-target" :class="{ unfolded: unfolded }">
        <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
            <div class="note-element" :class="{ unfolded: unfolded }">
                <header class="note-element-title">
                    <span
                        class="note-element-toggle"
                        @click="toggleElement"
                        :class="{ unfolded: unfolded, folded: !unfolded }"
                    >
                        {{ elementName }}
                    </span>
                    <spinner :show="showSpinner" @done="showSpinner = false"/>
                    <span class="note-element-toolbar">
                        <button
                            @click="copyElement"
                            class="copy"
                            title="Inhalt in die Zwischenablage kopieren"
                        ></button>
                        <button @click="removeElement" class="remove" title="Textfeld löschen"></button>
                    </span>
                </header>
                <textarea
                    ref="noteText"
                    class="note-element-content"
                    v-model="element.attributes.text"
                    @blur="autoSave"
                    @keyup="countChars"
                    @focus="setInfo"
                    v-show="unfolded"
                />
            </div>
            <div class="note-element-char-counter" v-show="unfolded" title="Anzahl der Zeichen">{{ charCounter }}</div>
        </div>
        <CoarseTarget
            v-show="unfolded"
            :element="coarseElement"
            :parentId="element.id"
            v-for="coarseElement in currentCoarseTargets"
            :key="coarseElement.id"
            @removeElement="removeCoarseElement"
            @setDefaultInfo="setDefaultInfo"
            @structureText="setCoarseStructureTexts"
        />
        <div class="note-element-adder" v-show="unfolded">
            <button class="add-note" @click="addCoarseTarget">
                <span class="add-note-icon"></span>
                <span class="add-note-text">Grobziel hinzufügen</span>
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './Spinner.vue';
import CoarseTarget from './CoarseTarget';

export default {
    name: 'IndicativeTarget',
    components: {
        Spinner,
        CoarseTarget
    },
    props: {
        element: Object,
        fineTargets: Object
    },
    data() {
        return {
            charCounter: 0,
            unfolded: true,
            showSpinner: false,
            structures_id: 17,
            currentCoarseTargets: [],
            coarseTargets: [],
            elementName: 'Richtziel',
            coarseStructureTexts: []
        };
    },
    mounted() {
        this.countChars();
        this.getCoarseTargets();
        this.getElementsText();
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    watch: {
        coarseStructureTexts: {
            handler: function() {
                this.getElementsText();
            },
            deep: true
        }
    },
    methods: {
        autoSave: function() {
            let view = this;
            this.setDefaultInfo();

            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {
                    view.showSpinner = true;
                    view.getElementsText();
                })
                .catch(error => console.log(error));
        },
        removeElement: function() {
            let view = this;
            if (!confirm('Möchten Sie das Richtziel und alle darunter liegenden Ziele wirklich löschen?')) {
                return;
            }
            axios
                .delete('./api/textfields/' + view.element.id, {
                    structures_id: view.structures_id,
                    plans_id: this.$store.state.plan.id
                })
                .then(function() {
                    view.$emit('removeElement', view.element.id);
                })
                .catch(error => console.log(error));
        },
        removeCoarseElement(elementId) {
            this.coarseStructureTexts = this.coarseStructureTexts.filter(x => {
                return x.id !== elementId;
            });
            this.updateElements();
        },
        updateElements() {
            this.coarseTargets = [];
            this.currentCoarseTargets = [];
            this.getCoarseTargets();
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
            text = this.elementName + '\n' + text;
            navigator.clipboard.writeText(text).then(
                function() {},
                error => {
                    console.log(error);
                }
            );
        },
        getCoarseTargets() {
            let view = this;
            axios
                .get('./api/textfields/' + view.plan.id + '/18')
                .then(function(response) {
                    if (response.data.data.length > 0) {
                        let elements = response.data;
                        elements.data.forEach(element => {
                            view.coarseTargets.push(element);
                        });
                        view.getCurrentCoarseTargets();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getCurrentCoarseTargets() {
            let view = this;
            this.coarseTargets.forEach(element => {
                let metadata = JSON.parse(element.attributes.metadata);
                if (metadata.parentId == view.element.id) {
                    view.currentCoarseTargets.push(element);
                }
            });
        },
        addCoarseTarget() {
            let view = this;
            let metadata = {};
            metadata.parentId = this.element.id;

            axios
                .post('./api/textfields', {
                    structures_id: 18,
                    text: '',
                    plans_id: view.$store.state.plan.id,
                    metadata: JSON.stringify(metadata)
                })
                .then(function() {
                    view.updateElements();
                })
                .catch(error => console.log(error));
        },
        setInfo() {
            this.$store.state.info = { id: this.structures_id, title: this.elementName };
        },
        setDefaultInfo() {
            this.$emit('setDefaultInfo');
        },
        setCoarseStructureTexts(coarseText) {
            let textObj = this.coarseStructureTexts.find(x => x.id == coarseText.id);
            let foundText = textObj != undefined;
            if (foundText) {
                textObj.text = coarseText.text;
            } else {
                this.coarseStructureTexts.push(coarseText);
                this.coarseStructureTexts.sort((a, b) => {
                    if (a.id > b.id) return 1;
                    if (b.id > a.id) return -1;
                });
            }
        },
        getElementsText() {
            let text = '';
            text = text + '<h3>' + this.elementName + '</h3>';
            text = text + '<p>' + this.element.attributes.text + '</p><br>';
            this.coarseStructureTexts.forEach(textObj => {
                text = text + textObj.text;
            });
            this.$emit('structureText', { text: text, id: this.element.id });
        }
    }
};
</script>
