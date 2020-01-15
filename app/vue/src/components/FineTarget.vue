<template>
    <div class="note-element-wrapper" :class="{ unfolded: unfolded }">
        <div class="note-element" :class="{ unfolded: unfolded }">
            <header class="note-element-title">
                <span class="note-element-toggle" @click="toggleElement" :class="{ unfolded: unfolded, folded: !unfolded }">
                    Feinziel
                </span>
                
                <span class="note-element-toolbar">
                    <select v-model="dimension" @change="autoSave()">
                        <option value="kognitiv">kognitiv</option>
                        <option value="affektiv">affektiv</option>
                        <option value="psychomotorisch">psychomotorisch</option>
                    </select>
                    <select v-model="level" @change="autoSave()">
                        <option
                            v-show="dimension == 'kognitiv'"
                            v-for="val in kognitivLevels" 
                            :key="val"
                            :value="val"
                        >
                            {{val}}
                        </option>
                        <option
                            v-show="dimension == 'affektiv'"
                            v-for="val in affektivLevels" 
                            :key="val"
                            :value="val"
                        >
                            {{val}}
                        </option>
                        <option
                            v-show="dimension == 'psychomotorisch'"
                            v-for="val in psychomotorischLevels" 
                            :key="val"
                            :value="val"
                        >
                            {{val}}
                        </option>
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
    name: 'FineTarget',
    props: {
        element: Object,
        parentId: String
    },
    data() {
        return{
            charCounter: 0,
            unfolded: true, 
            structures_id: 19,
            dimension: '',
            level: '',
            kognitivLevels: ['wissen', 'verstehen', 'anwenden', 'analysieren', 'synthetisieren'],
            affektivLevels: ['affektiv_A', 'affektiv_B', 'affektiv_C'],
            psychomotorischLevels: ['psychomotorisch_A', 'psychomotorisch_B', 'psychomotorisch_C'],
        }
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
            let metadata = {}
            metadata.indicativeTargetId = this.parentId;
            metadata.dimension = this.dimension;
            metadata.level = this.level;

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
        }
    }
}
</script>