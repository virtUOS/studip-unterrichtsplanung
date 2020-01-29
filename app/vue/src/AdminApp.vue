<template>
    <div id="admin-app">
        <div class="admin-box">
            <div class="admin-box-header">
                <span class="headline">Infotexte bearbeiten</span>
                <spinner :show="showSpinner" @done="showSpinner = false"/>
                <select @change="selectElement" v-model="selectedStructure">
                    <option v-for="element in elementList" :key="element.id" :value="element.id">
                        {{element.name}}
                    </option>
                </select>
            </div>
            <div class="selected-text-wrapper">
                <textarea v-model="selectedText" ref="selectedText" class="selected-text"/>
            </div>
            <button class="button admin-box-button" @click="storeText">Infotext &#8222;{{selectedName}}&#8220; speichern</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Spinner from './components/Spinner.vue';

export default {
    name: 'AdminApp',
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
            selectedName: '',
            showSpinner: false
        }
    },
    mounted(){
        console.log('hello admin');
        this.getStructures();
    },
    methods:{
        getStructures() {
            let view = this;
            axios
            .get('./api/structures')
            .then(response => {
                if(response.data.data.length > 0) {
                    view.structures = response.data.data;
                    view.getInfoTexts();
                }
            })
            .catch(error => {console.log(error);})
        },
        getInfoTexts() {
            let view = this;
            axios
            .get('./api/infotexts')
            .then(response => {
                if(response.data.data.length > 0) {
                    view.infoTexts = response.data.data;
                    view.setTextList();
                }
            })
            .catch(error => {console.log(error);})
        },
        setTextList() {
            this.structures.forEach(structure => {
                let listElement = {};
                listElement.id = structure.id;
                listElement.name = structure.attributes.name;
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
            this.selectedStructure = this.elementList[0].id;
            this.initCKE();
            this.selectElement();
        },
        createInfoText(listElement) {
            let view = this;

            axios
                .post('./api/infotexts',{
                    structures_id: listElement.id,
                    templates_id: 0,
                    text: ''
                })
                .then(response => {
                    listElement.text_id = response.data.id;
                    view.elementList.push(listElement);
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
        initCKE() {
            let view = this;
            STUDIP.wysiwyg.replace(view.$refs.selectedText);
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
                templates_id: 0,
                text: view.selectedText
            })
            .then(function(){
                view.showSpinner = true;
            })
            .catch(error => console.log(error));
        },
    }
};
</script>
