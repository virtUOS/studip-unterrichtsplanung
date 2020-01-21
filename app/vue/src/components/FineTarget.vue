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
                        <option value="kognitiv">kognitiv</option>
                        <option value="affektiv">affektiv</option>
                        <option value="psychomotorisch">psychomotorisch</option>
                    </select>
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
                </div>
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
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'FineTarget',
    props: {
        element: Object,
        parentId: String
    },
    data() {
        return {
            charCounter: 0,
            unfolded: false,
            structures_id: 19,
            dimension: '',
            level: '',
            elementName: 'Feinziel',
            kognitivLevels: ['wissen', 'verstehen', 'anwenden', 'analysieren', 'synthetisieren'],
            affektivLevels: ['affektiv_A', 'affektiv_B', 'affektiv_C'],
            psychomotorischLevels: ['psychomotorisch_A', 'psychomotorisch_B', 'psychomotorisch_C']
        };
    },
    mounted() {
        this.countChars();
        this.dimension = this.metadata.dimension;
        this.level = this.metadata.level;
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
            metadata.level = this.level;
            this.$emit('setDefaultInfo');

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
        setInfo() {
            this.$store.state.info = {'id': this.structures_id , 'title': this.elementName};
        },
    }
};
</script>
