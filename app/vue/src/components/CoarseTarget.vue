<template>
    <div class="target-wrapper coarse-target" :class="{ unfolded: unfolded }">
        <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
            <div class="note-element" :class="{ unfolded: unfolded }">
                <header class="note-element-title">
                    <span class="note-element-toggle" @click="toggleElement" :class="{ unfolded: unfolded, folded: !unfolded }">
                        Grobziel
                    </span>

                    <span class="note-element-toolbar">
                        <select v-model="dimension" @change="autoSave">
                            <option value="kognitiv">kognitiv</option>
                            <option value="affektiv">affektiv</option>
                            <option value="psychomotorisch">psychomotorisch</option>
                        </select>
                        <button
                            @click="copyElement"
                            class="copy"
                            title="alles auswählen und in Zischenablage kopieren"
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
    </div>
</template>

<script>
import axios from 'axios';
import FineTarget from './FineTarget'

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
        return{
            charCounter: 0,
            unfolded: true, 
            structures_id: 18,
            currentFineTargets: [],
            fineTargets: [],
            dimension: ''
        }
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
            let metadata = {}
            metadata.indicativeTargetId = this.parentId;
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
            console.log('remove element');
            if (!confirm('Möchten Sie das Textfeld ' + view.element.name + ' wirklich löschen?')) {
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
        updateElements(){},
        countChars() {
            let string = this.$refs.noteText.value;
            string = string.replace(/\s/g, '');
            this.charCounter = string.length;
        },
        toggleElement() {
            this.unfolded = !this.unfolded;
        },
        copyElement() {
            this.$refs.noteText.select();
            document.execCommand('copy');
        },

        getFineTargets(){
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

        getCurrentFineTargets(){
            let view = this;
            this.fineTargets.forEach(element => {
                let elementMetadata = JSON.parse(element.attributes.metadata);
                if (elementMetadata.indicativeTargetId == view.element.id) {
                    view.currentFineTargets.push(element);
                }
            });
        }
    }
}
</script>
