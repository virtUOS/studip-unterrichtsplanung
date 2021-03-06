<template>
    <div id="admin-app">
        <div class="admin-box-wrapper">
            <div class="admin-box">
                <div class="admin-box-header">
                    <span class="headline">Infotexte bearbeiten</span>
                    <spinner :show="showSpinner" @done="showSpinner = false"/>
                    <select @change="selectElement" v-model="selectedStructure">
                        <option v-for="element in elementList" :key="element.text_id" :value="element.id">
                            {{element.name}}
                        </option>
                    </select>
                    <select @change="selectDidactics" v-model="selectedDiadactics">
                        <option v-for="diadactics in diadacticsList" :key="diadactics.id" :value="diadactics.id">
                            {{diadactics.name}}
                        </option>
                    </select>
                </div>
                <div class="selected-text-wrapper">
                    <textarea v-model="selectedText" ref="selectedText" class="selected-text"/>
                </div>
                <button class="button admin-box-button" @click="storeText">Infotext &#8222;{{selectedName}}&#8220; speichern</button>
            </div>
            <div class="preview-box">
                    <div class="infobox-wrapper">
                        <h3 class="header" >{{ selectedName }}</h3>
                        <div class="infobox-content">
                            <p v-html="selectedText"></p>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './components/Spinner.vue';
import mixin from './mixins/mixin.js';

export default {
    name: 'AdminApp',
    mixins: [mixin],
    components: {
        Spinner
    },
    data(){
        return {
            infoTexts: [],
            structures: [],
            elementList: [],
            selectedStructure: '',
            selectedText: '',
            selectedTextId: '',
            selectedName: '',
            showSpinner: false,
            diadacticsList: [
                {id: 1, name: 'Allgemeindidaktisch'},
                {id: 2, name: 'Mathematikdidaktisch'},
                {id: 3, name: 'Sportdidaktisch'},
                {id: 4, name: 'Geographiedidaktisch'}
            ],
            selectedDiadactics: 1,
        }
    },
    computed: {
        specialStructures: function() {
            let special = [];
            special.push({id: '-1', attributes:{name: 'Auswählen oder Erstellen', id: '-1', parent_id: 0}});
            special.push({id: '-2', attributes:{name: 'Einen Plan erstellen', id: '-2', parent_id: 0}});
            special.push({id: '-3', attributes:{name: 'Planübersicht', id: '-3', parent_id: 0}});
            special.push({id: '-4', attributes:{name: 'Einen Plan bearbeiten', id: '-4', parent_id: 0}});
            special.push({id: '-5', attributes:{name: 'Verlaufsplan', id: '-5', parent_id: 0}});

            return special;
        }
    },
    mounted(){
        this.getStructures();
    },
    methods:{
        getStructures() {
            let view = this;
            axios
            .get('./api/structures')
            .then(response => {
                view.structures = view.specialStructures;
                if(response.data.data.length > 0) {
                    view.structures = view.structures.concat(response.data.data);
                    view.getInfoTexts();
                }
            })
            .catch(error => {console.log(error);})
        },
        getInfoTexts() {
            let view = this;
            axios
            .get('./api/infotexts/' + this.selectedDiadactics)
            .then(response => {
                if(response.data.data.length > 0) {
                    view.infoTexts = response.data.data;
                } else {
                    view.infoTexts = [];
                }
                view.setTextList();
            })
            .catch(error => {console.log(error);})
        },
        setTextList() {
            this.elementList = [];
            this.structures.forEach(structure => {
                let listElement = {};
                listElement.id = structure.id;
                listElement.name = this.getStructureNameByTemplate(structure, this.selectedDiadactics);
                listElement.text = '';
                listElement.text_id = null;
                let text = this.infoTexts.find(x => x.attributes.structures_id == structure.id);
                if(text != undefined) {
                    listElement.text = text.attributes.text;
                    listElement.text_id = text.id
                }
                if (listElement.text_id !== null) {
                    this.elementList.push(listElement);
                } else {
                    this.createInfoText(listElement);
                }
            });
            this.initCKE();
            this.sortElementList();
            
        },
        createInfoText(listElement) {
            let view = this;
            axios
                .post('./api/infotexts',{
                    structures_id: listElement.id,
                    templates_id: view.selectedDiadactics,
                    text: ''
                })
                .then(response => {
                    listElement.text_id = response.data.id;
                    view.elementList.push(listElement);
                    view.sortElementList();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        selectElement() {
            let element = this.elementList.find(x => x.id == this.selectedStructure);
            this.selectedName = element.name.trim();
            this.selectedText = element.text;
            this.selectedTextId = element.text_id;
            var wysiwyg_editor = CKEDITOR.instances[this.$refs.selectedText.id];
            wysiwyg_editor.setData(this.selectedText);
        },
        selectDidactics() {
            this.getStructures();
        },
        initCKE() {
            let view = this;
            if (CKEDITOR.instances['wysiwyg0'] == undefined) {
                STUDIP.wysiwyg.replace(view.$refs.selectedText);
            }
            let wysiwyg_editor = CKEDITOR.instances[view.$refs.selectedText.id];

            wysiwyg_editor.on('change', function() {
                view.selectedText = wysiwyg_editor.getData();
            });
            wysiwyg_editor.on('blur', function() {
            });
            wysiwyg_editor.on('instanceReady', function(e) {
            });
        },
        storeText() {
            let view = this;
            let wysiwyg_editor = CKEDITOR.instances[view.$refs.selectedText.id];
            let text = wysiwyg_editor.getData();

            axios
            .put('./api/infotexts/' + view.selectedTextId, {
                structures_id: view.selectedStructure,
                templates_id: view.selectedDiadactics,
                text: view.selectedText
            })
            .then(function(){
                view.showSpinner = true;
            })
            .catch(error => console.log(error));
        },
        sortElementList() {
            this.elementList.sort((a, b) => {
                if (a.name.toLowerCase() > b.name.toLowerCase()) return 1;
                if (b.name.toLowerCase() > a.name.toLowerCase()) return -1;
            });
            let view = this;
            this.elementList = this.elementList.filter((element) => {
                if (view.selectedDiadactics == 2) {
                    let discard = ['25', '26', '27', '28', '32', '33', '34', '35'];
                    return !discard.includes(element.id);
                }
                if(view.selectedDiadactics != 3) {
                    let discard = ['36'];
                    return !discard.includes(element.id);
                }

                return true;
            });
            this.selectedStructure = this.elementList[0].id;
            this.selectElement();
        }
    }
};
</script>
