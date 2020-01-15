<template>
    <div class="target-wrapper">
        <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
            <div class="note-element" :class="{ unfolded: unfolded }">
                <header class="note-element-title">
                    <span class="note-element-toggle" @click="toggleElement" :class="{ unfolded: unfolded, folded: !unfolded }">
                        Richtziel
                    </span>
                    
                    <span class="note-element-toolbar">
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
                    @blur="autoSave()"
                    @keyup="countChars()"
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
                    @removeElement="updateElements"
                />
    </div>
</template>

<script>
import axios from 'axios';
import CoarseTarget from './CoarseTarget'
import FineTarget from './FineTarget'

export default {
    name: 'IndicativeTarget', 
    props: {
        element: Object,
        fineTargets: Object
    },
    data() {
        return {
            charCounter: 0,
            unfolded: true,
            structures_id: 17,
            currentCoarseTargets: [],
            coarseTargets: []
        };
    },
    components: {
        CoarseTarget,
        FineTarget
    },
    mounted() {
        // console.log(this.element);
        this.countChars();
        this.getCoarseTargets();
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    methods: {
        autoSave: function() {
            let view = this;
            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.structures_id,
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
        getCoarseTargets(){
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
        getCurrentCoarseTargets(){
            let view = this;
            this.coarseTargets.forEach(element => {
                let metadata = JSON.parse(element.attributes.metadata);
                if (metadata.indicativeTargetId == view.element.id) {
                    view.currentCoarseTargets.push(element);
                }
            });
        }
    }
}
</script>