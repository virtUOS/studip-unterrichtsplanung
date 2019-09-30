<template>
    <div class="add-plan">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / Plan hinzufügen
        </h1>
        <div class="content-wrapper">
            <div class="plan-metadata">
                <h3 class="plan-metadata-header">{{ planTypeFullText }}</h3>
                <div class="plan-metadata-fieldset">
                    <label for="planTitle">Plantitel:</label>
                    <input type="text" name="planTitle" v-model="planTitle" /> <br />
                    <label for="typeOfSchool">Schulform:</label>
                    <input type="text" name="typeOfSchool" v-model="typeOfSchool" /> <br />
                    <label for="gradeLevel">Klassenstufe:</label>
                    <input type="text" name="gradeLevel" v-model="gradeLevel" /> <br />
                    <label for="subject">Fach:</label> <input type="text" name="subject" v-model="subject" /> <br />
                    <label for="topic">Thema der Unterrichtsstunde:</label>
                    <input type="text" name="topic" v-model="topic" /><br />
                    <label for="date">Datum:</label><input type="date" name="date" v-model="date" /> <br />
                    <label for="time">Uhrzeit:</label> <input type="time" name="time" v-model="time" /> <br />
                </div>
                <div class="plan-metadata-buttons">
                    <button class="button" @click="createPlan">Plan erstellen</button>
                    <router-link to="/"><button class="button">zurück zur Übersicht</button></router-link>
                </div>
                <div class="plan-metadata-errors">
                    <p v-for="error in errors" :key="error">{{ error }}</p>
                </div>
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import axios from 'axios';

export default {
    name: 'AddPlan',
    components: {
        InfoBox
    },
    data() {
        return {
            errors: []
        };
    },
    computed: {
        planTypeFullText() {
            let fullText = '';
            switch (this.$route.params.planType) {
                case '1':
                    fullText = 'Bildungswissenschaftlich';
                    break;
                case '2':
                    fullText = 'Fachdidaktik Mathematik';
                    break;
                case '3':
                    fullText = 'Fachdidaktik Sport';
                    break;
                case '4':
                    fullText = 'Fachdidaktik Geologie';
                    break;
            }
            return fullText;
        },
        infoBoxTitle() {
            return 'Einen Plan erstellen';
        }
    },
    methods: {
        createPlan: function() {
            if (!this.planTitle) {
                this.errors.push('Bitte geben Sie einen Plantitel ein!');
                return false;
            } else {
                this.errors = [];
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
