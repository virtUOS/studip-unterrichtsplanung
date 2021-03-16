<template>
    <div class="target-wrapper fine-target" :class="{ unfolded: unfolded }">
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
                <div class="target-metadata-box" v-show="unfolded">
                    <label for="dimension">Dimension</label>
                    <select name="dimension" v-model="dimension" @change="changeDimension">
                        <option v-for="(dim, index) in dimensions" :key="index" :value="dim">{{ dim }}</option>
                    </select>
                    <span v-if="showSteps">
                        <label for="level">Stufe</label>
                        <select name="level" v-model="level" @change="autoSave">
                            <option v-show="dimension == 'kognitiv'" v-for="val in kognitivLevels" :key="val" :value="val">
                                {{ val }}
                            </option>
                            <option v-show="dimension == 'affektiv'" v-for="val in affektivLevels" :key="val" :value="val">
                                {{ val }}
                            </option>
                            <option
                                v-show="dimension == 'psychomotorisch'"
                                v-for="val in psychomotorischLevels"
                                :key="val"
                                :value="val"
                            >
                                {{ val }}
                            </option>
                        </select>
                    </span>
                </div>
                <textarea
                    ref="noteText"
                    class="note-element-content"
                    v-model="element.attributes.text"
                    @blur="autoSave()"
                    @keyup="countChars()"
                    @focus="$emit('setInfo', {id: element.attributes.structures_id, name: elementName})"
                    v-show="unfolded"
                />
            </div>
            <div class="note-element-char-counter" v-show="unfolded" title="Anzahl der Zeichen">{{ charCounter }}</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './Spinner.vue';

export default {
    name: 'FineTarget',
    components: {
        Spinner
    },
    props: {
        element: Object,
        parentId: String
    },
    data() {
        return {
            charCounter: 0,
            unfolded: true,
            showSpinner: false,
            structures_id: 22,
            dimension: '',
            level: '',
            elementName: 'Feinziel',
            kognitivLevels: ['Wissen', 'Verstehen', 'Anwenden', 'Analysieren', 'Synthetisieren', 'Evaluation'],
            affektivLevels: ['Aufmerksamkeit', 'Reagieren', 'Werten', 'Wertordnung', 'Bestimmtsein durch Werte'],
            psychomotorischLevels: ['Imitation', 'Manipulation', 'Präzision', 'Handlungsgliederung', 'Neutralisierung']
        };
    },
    mounted() {
        this.countChars();
        this.dimension = this.metadata.dimension;
        this.level = this.metadata.level;
        this.getElementsText();
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        metadata() {
            return JSON.parse(this.element.attributes.metadata);
        },
        showSteps() {
            let template = this.plan.attributes.templates_id;

            if(template == 3 || template == 4) { // 3 => Sport; 4 => Geo
                return false;
            } else if(this.dimension == undefined) {
                return false;
            } else {
                return true;
            }
        },
        dimensions() {
            let dimensions = ['kognitiv', 'affektiv'];
            let template = this.plan.attributes.templates_id;
            if(template == 3) {
                dimensions.push('motorisch');
                dimensions.push('sozial');
            } else if(template == 4) {
                dimensions.push('instrumentell');
                dimensions.push('aktional');
            } else {
                dimensions.push('psychomotorisch');
            }

            return dimensions;
        }
    },
    methods: {
        autoSave: function() {
            let view = this;
            let metadata = {};
            metadata.parentId = this.parentId;
            metadata.dimension = this.dimension;
            metadata.level = this.level;
            this.$emit('setDefaultInfo');

            axios
                .put('./api/textfields/' + view.element.id, {
                    structures_id: view.structures_id,
                    text: view.element.attributes.text,
                    plans_id: this.$store.state.plan.id,
                    metadata: JSON.stringify(metadata)
                })
                .then(function() {
                    view.showSpinner = true;
                    view.getElementsText();
                })
                .catch(error => console.log(error));
        },
        removeElement: function() {
            let view = this;
            if (!confirm('Möchten Sie das Feinziel wirklich löschen?')) {
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
            text = this.elementName + '\nDimension: ' + this.dimension + '\nStufe: ' + this.level + '\n' + text;
            navigator.clipboard.writeText(text).then(
                function() {},
                error => {
                    console.log(error);
                }
            );
        },
        changeDimension() {
            this.level = '';
            this.autoSave();
        },
        getElementsText() {
            let text = '';
            text = text + '<h5>' + this.elementName + '</h5>';
            text = text + '<h6>Dimension: ' + this.dimension + '</h6>';
            text = text + '<h6>Stufe: ' + this.level + '</h6>';
            text = text + '<p>' + this.element.attributes.text + '</p><br>';
            this.$emit('structureText', { text: text, id: this.element.id });
        },
        setInfo() {
            this.$store.state.info = { id: this.structures_id, title: this.elementName };
        }
    }
};
</script>
