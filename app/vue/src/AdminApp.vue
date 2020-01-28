<template>
    <div id="admin-app">
        <div class="admin-box">
            <div class="admin-box-header">
                <span class="headline">Infotexte bearbeiten</span>
                <select @change="selectElement" v-model="selectedStructure">
                    <option v-for="element in elementList" :key="element.id" :value="element.id">
                        {{element.name}}
                    </option>
                </select>
            </div>
            <div class="selected-text-wrapper">
                <textarea v-model="selectedText" ref="selectedText" class="selected-text"/>
            </div>
            <button class="button admin-box-button" @click="storeText">Text speichern</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'AdminApp',
    data(){
        return {
            infoTexts: [],
            structures: [],
            elementList: [],
            selectedStructure: '',
            selectedText: ''
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
        storeText() {
            let wysiwyg_editor = CKEDITOR.instances[view.$refs.selectedText.id];
            let text = wysiwyg_editor.getData();

            axios
            .put()
            .then()
            .catch();
        },
        setTextList() {
            this.structures.forEach(structure => {
                let listElement = {};
                listElement.id = structure.id;
                listElement.name = structure.attributes.name;
                listElement.text = '';
                let text = this.infoTexts.find(x => x.attributes.structures_id == structure.id);
                if(text != undefined) {
                    listElement.text = text.attributes.text;
                }
                this.elementList.push(listElement);
            });
            this.selectedStructure = this.elementList[0].id;
            this.initCKE();
            this.selectElement();
        },
        selectElement() {
            this.selectedText = this.elementList.find(x => x.id == this.selectedStructure).text;
            var wysiwyg_editor = CKEDITOR.instances[this.$refs.selectedText.id];
            wysiwyg_editor.setData(this.selectedText);
        },
        initCKE() {
            let view = this;
            STUDIP.wysiwyg.replace(view.$refs.selectedText);
            let wysiwyg_editor = CKEDITOR.instances[view.$refs.selectedText.id];
            wysiwyg_editor.on('blur', function() {
                // view.autoSave(wysiwyg_editor.getData());
            });
            wysiwyg_editor.on('change', function() {
                // view.countChars();
            });
            wysiwyg_editor.on('instanceReady', function(e) {
            });
        },
    }
};
</script>
