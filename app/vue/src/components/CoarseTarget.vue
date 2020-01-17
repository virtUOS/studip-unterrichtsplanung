<template>
    <div class="target-wrapper coarse-target" :class="{ unfolded: unfolded }">
        <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
            <div class="note-element" :class="{ unfolded: unfolded }">
                <header class="note-element-title">
                    <span
                        class="note-element-toggle"
                        @click="toggleElement"
                        :class="{ unfolded: unfolded, folded: !unfolded }"
                    >
                        Grobziel
                    </span>

                    <span class="note-element-toolbar">
                        <button
                            @click="copyElement"
                            class="copy"
                            title="Inhalt in die Zwischenablage kopieren"
                        ></button>
                        <button @click="removeElement" class="remove" title="Textfeld löschen"></button>
                    </span>
                </header>
                <div class="target-metadata-box" v-show="unfolded">
                    <label for="dimension">Dimension</label>
                    <select name="dimension" v-model="dimension" @change="autoSave">
                        <option value="kognitiv">kognitiv</option>
                        <option value="affektiv">affektiv</option>
                        <option value="psychomotorisch">psychomotorisch</option>
                    </select>
                </div>
                <textarea
                    ref="noteText"
                    class="note-element-content"
                    v-model="element.attributes.text"
                    @blur="autoSave"
                    @keyup="countChars"
                    v-show="unfolded"
                />
            </div>
            <div class="note-element-char-counter" v-show="unfolded" title="Anzahl der Zeichen">{{ charCounter }}</div>
        </div>
        <FineTarget
            v-show="unfolded"
            :element="fineElement"
            :parentId="element.id"
            v-for="fineElement in currentFineTargets"
            :key="fineElement.id"
            @removeElement="updateElements"
        />
        <div class="note-element-adder" v-show="unfolded">
            <button class="add-note" @click="addFineTarget">
                <span class="add-note-icon"></span>
                <span class="add-note-text">Feinziel hinzufügen</span>
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import FineTarget from './FineTarget';

export default {
    name: 'CoarseTarget',
    components: {
        FineTarget
    },
    props: {
        element: Object,
        parentId: String
    },
    data() {
        return {
            charCounter: 0,
            unfolded: false,
            structures_id: 18,
            currentFineTargets: [],
            fineTargets: [],
            dimension: ''
        };
    },
    mounted() {
        this.countChars();
        this.getFineTargets();
        this.dimension = this.metadata.dimension;
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        metadata() {
            return JSON.parse(this.element.attributes.metadata);
        }
    },
    methods: {
        autoSave: function() {
            let view = this;
            let metadata = {};
            metadata.parentId = this.parentId;
            metadata.dimension = this.dimension;

            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id,
                    metadata: JSON.stringify(metadata)
                })
                .then(function() {})
                .catch(error => console.log(error));
        },
        removeElement: function() {
            let view = this;
            if (!confirm('Möchten Sie das Grobziel und alle darunter liegenden Ziele wirklich löschen?')) {
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
        updateElements() {
            this.fineTargets = [];
            this.currentFineTargets = [];
            this.getFineTargets();
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
            let dim = this.dimension;
            let text = this.element.attributes.text;
            text = 'Grobziel\nDimension: ' + dim + '\n' + text;
            navigator.clipboard.writeText(text).then(
                function() {},
                error => {
                    console.log(error);
                }
            );
        },
        getFineTargets() {
            let view = this;
            axios
                .get('./api/textfields/' + view.plan.id + '/19')
                .then(function(response) {
                    if (response.data.data.length > 0) {
                        let elements = response.data;
                        elements.data.forEach(element => {
                            view.fineTargets.push(element);
                            console.log(element);
                        });
                        view.getCurrentFineTargets();
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getCurrentFineTargets() {
            let view = this;
            this.fineTargets.forEach(element => {
                let elementMetadata = JSON.parse(element.attributes.metadata);
                if (elementMetadata.parentId == view.element.id) {
                    view.currentFineTargets.push(element);
                }
            });
        },
        addFineTarget() {
            let view = this;
            let metadata = {};
            metadata.parentId = this.element.id;

            axios
                .post('./api/textfields', {
                    structures_id: 19,
                    text: '',
                    plans_id: view.$store.state.plan.id,
                    metadata: JSON.stringify(metadata)
                })
                .then(function() {
                    view.updateElements();
                })
                .catch(error => console.log(error));
        }
    }
};
</script>
