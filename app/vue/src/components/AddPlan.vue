<template>
    <div class="add-plan">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / Plan hinzufügen
        </h1>
        <div class="content-wrapper">
            <div class="plan-metadata">
                <h3 class="plan-metadata-header">{{ getPlanTemplateName(this.$route.params.planType) }}</h3>
                <div class="plan-metadata-fieldset">
                    <label for="planTitle">Plantitel:</label>
                    <input type="text" name="planTitle" v-model="planTitle" :class="{'plan-error': errors.planTitle}"/>
                    <span v-if="errors.planTitle">Bitte geben Sie einen Plantitel ein</span>
                    <br />

                    <label for="typeOfSchool">Schulform:</label>
                    <input type="text" name="typeOfSchool" v-model="typeOfSchool" :class="{'plan-error': errors.typeOfSchool}"/>
                    <span v-if="errors.typeOfSchool">Bitte geben Sie einen Schulform ein</span>
                    <br />
                    
                    <label for="gradeLevel">Klassenstufe:</label>
                    <input type="text" name="gradeLevel" v-model="gradeLevel" :class="{'plan-error': errors.gradeLevel}"/>
                    <span v-if="errors.gradeLevel">Bitte geben Sie eine Klassenstufe ein</span>
                    <br />

                    <label for="subject">Fach:</label>
                    <input type="text" name="subject" v-model="subject" :class="{'plan-error': errors.subject}"/>
                    <span v-if="errors.subject">Bitte geben Sie das Fach ein</span>
                    <br />

                    <label for="topic">Thema der Unterrichtsstunde:</label>
                    <input type="text" name="topic" v-model="topic" :class="{'plan-error': errors.topic}"/>
                    <span v-if="errors.topic">Bitte geben Sie das Thema der Unterrichtsstunde ein</span>
                    <br />

                    <label for="date">Datum:</label>
                    <input type="date" name="date" v-model="date" :class="{'plan-error': errors.date}"/>
                    <span v-if="errors.date">Bitte geben Sie ein Datum ein</span>
                    <br />
                    
                    <label for="time">Uhrzeit:</label>
                    <input type="time" name="time" v-model="time" :class="{'plan-error': errors.time}"/>
                    <span v-if="errors.time">Bitte geben Sie die Uhrzeit ein</span>
                    <br />
                </div>
                <div class="plan-metadata-buttons">
                    <button class="button" @click="createPlan">Plan erstellen</button>
                    <router-link to="/"><button class="button">zurück zur Übersicht</button></router-link>
                </div>
            </div>
            <InfoBox :structureId="structureId" :structureName="structureName" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import axios from 'axios';
import mixin from './../mixins/mixin.js';

export default {
    name: 'AddPlan',
    mixins: [mixin],
    components: {
        InfoBox
    },
    data() {
        return {
            errors: {planTitle: false, typeOfSchool: false, gradeLevel: false,  subject: false, topic: false, date: false, time: false},
            planTitle: '',
            typeOfSchool: '',
            gradeLevel: '',
            subject: '',
            topic: '',
            date: '',
            time: '',
            structureId: -2
        };
    },
    computed: {
        structureName() {
            return 'Einen Plan erstellen';
        }
    },
    watch:{
        planTitle: function(val) {
            if(val.trim() != '') this.errors.planTitle = false;
        },
        typeOfSchool: function(val) {
            if(val.trim() != '') this.errors.typeOfSchool = false;
        },
        gradeLevel: function(val) {
            if(val.trim() != '') this.errors.gradeLevel = false;
        },
        subject: function(val) {
            if(val.trim() != '') this.errors.subject = false;
        },
        topic: function(val) {
            if(val.trim() != '') this.errors.topic = false;
        },
        date: function(val) {
            if(val.trim() != '') this.errors.date = false;
        },
        time: function(val) {
            if(val.trim() != '') this.errors.time = false;
        },
    },
    methods: {
        createPlan: function() {
            let error = false
            if (this.planTitle.trim() == '') { this.errors.planTitle = true; error = true}
            if (this.typeOfSchool.trim() == '') { this.errors.typeOfSchool = true; error = true}
            if (this.gradeLevel.trim() == '') { this.errors.gradeLevel = true; error = true}
            if (this.subject.trim() == '') { this.errors.subject = true; error = true}
            if (this.topic.trim() == '') { this.errors.topic = true; error = true}
            if (this.date.trim() == '') { this.errors.date = true; error = true}
            if (this.time.trim() == '') { this.errors.time = true; error = true}

            if(error) {
                return false;
            }
            let view = this;
            let metadata = {};
            metadata.typeOfSchool = this.typeOfSchool;
            metadata.gradeLevel = this.gradeLevel;
            metadata.subject = this.subject;
            metadata.topic = this.topic;
            metadata.date = this.date;
            metadata.time = this.time;

            axios
                .post('./api/plans', {
                    templates_id: this.$route.params.planType,
                    name: this.planTitle,
                    metadata: JSON.stringify(metadata)
                })
                .then(function(response) {
                    let planId = response.data.id;
                    view.$router.push({ path: '/plan/' + planId });
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
