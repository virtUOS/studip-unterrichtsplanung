<template>
    <div class="edit-plan">
        <h1>
            <a href="#" @click="leave('/')"><span class="nav home"></span></a> / 
            <a href="#" @click="leave('/plan/' + $store.state.plan.id)">{{ this.$store.state.plan.attributes.name }} </a> / Plan bearbeiten
        </h1>
        <div class="content-wrapper">
            <div class="plan-metadata">
                <h3 class="plan-metadata-header">{{ templatesName }}</h3>
                <div class="plan-metadata-fieldset">
                    <label for="planName">Plantitel:</label>
                    <input type="text" name="planName" v-model="planName" @change="changed = true" :class="{'plan-error': errors.planName}"/>
                    <span v-if="errors.planName">Bitte geben Sie einen Plantitel ein</span>
                    <br />

                    <label for="typeOfSchool">Schulform:</label>
                    <input type="text" name="typeOfSchool" v-model="metadata.typeOfSchool" @change="changed = true" :class="{'plan-error': errors.typeOfSchool}"/>
                    <span v-if="errors.typeOfSchool">Bitte geben Sie einen Schulform ein</span>
                    <br />

                    <label for="gradeLevel">Klassenstufe:</label>
                    <input type="text" name="gradeLevel" v-model="metadata.gradeLevel" @change="changed = true" :class="{'plan-error': errors.gradeLevel}"/>
                    <span v-if="errors.gradeLevel">Bitte geben Sie eine Klassenstufe ein</span>
                    <br />

                    <label for="subject">Fach:</label>
                    <input type="text" name="subject" v-model="metadata.subject" @change="changed = true" :class="{'plan-error': errors.subject}"/>
                    <span v-if="errors.subject">Bitte geben Sie das Fach ein</span>
                    <br />

                    <label for="topic">Thema der Unterrichtsstunde:</label>
                    <input type="text" name="topic" v-model="metadata.topic" @change="changed = true" :class="{'plan-error': errors.topic}"/>
                    <span v-if="errors.topic">Bitte geben Sie das Thema der Unterrichtsstunde ein</span>
                    <br />

                    <label for="date">Datum:</label
                    ><input type="date" name="date" v-model="metadata.date" @change="changed = true" :class="{'plan-error': errors.date}"/>
                    <span v-if="errors.date">Bitte geben Sie ein Datum ein</span>
                    <br />

                    <label for="time">Uhrzeit:</label>
                    <input type="time" name="time" v-model="metadata.time" @change="changed = true" :class="{'plan-error': errors.time}"/>
                    <span v-if="errors.time">Bitte geben Sie die Uhrzeit ein</span>
                    <br />
                </div>
                <div class="plan-metadata-buttons">
                    <button class="button accept" @click="storePlan">Plan speichern</button>
                    <button class="button cancel" @click="leave('/plan/' + $store.state.plan.id)">zurück zur Planübersicht</button>
                    <button class="button button-remove" @click="removePlan">Plan löschen</button>
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
            metadata: {},
            planName: '',
            templatesName: '',
            structureId: -4,
            structureName: 'Einen Plan bearbeiten',
            changed: false
        };
    },
    beforeMount() {
        let plan = this.$store.state.plan;
        if (plan.attributes != undefined) {
                this.metadata = JSON.parse(plan.attributes.metadata);
                this.planName = plan.attributes.name;
                this.templatesName = this.getPlanTemplateName(plan.attributes.templates_id);
        }
    },
    watch:{
        metadata: {
            deep: true,
            handler(val) {
                if(val.typeOfSchool.trim() != '') {this.errors.typeOfSchool = false;}
                if(val.gradeLevel.trim() != '') {this.errors.gradeLevel = false;}
                if(val.subject.trim() != '') {this.errors.subject = false;}
                if(val.topic.trim() != '') {this.errors.topic = false;}
                if(val.date.trim() != '') {this.errors.date = false;}
                if(val.time.trim() != '') {this.errors.time = false;}
            }
        },
        planName: function(val) {
            if(val.trim() != '') this.errors.planName = false;
        }
    },
    methods: {
        storePlan: function() {
            let error = false;
            if (this.planName.trim() == '') { this.errors.planName = true; error = true}
            if (this.metadata.typeOfSchool.trim() == '') { this.errors.typeOfSchool = true; error = true}
            if (this.metadata.gradeLevel.trim() == '') { this.errors.gradeLevel = true; error = true}
            if (this.metadata.subject.trim() == '') { this.errors.subject = true; error = true}
            if (this.metadata.topic.trim() == '') { this.errors.topic = true; error = true}
            if (this.metadata.date.trim() == '') { this.errors.date = true; error = true}
            if (this.metadata.time.trim() == '') { this.errors.time = true; error = true}

            if(error) {
                return false;
            }

            let view = this;
            axios
                .put('./api/plans/' + view.$store.state.plan.id, {
                    templates_id: view.$store.state.plan.attributes.templates_id,
                    name: view.planName,
                    metadata: JSON.stringify(view.metadata)
                })
                .then(function() {
                    view.reloadPlan();
                    view.$router.push({ path: '/plan/' + view.$store.state.plan.id });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        reloadPlan() {
            let view = this;
            axios
                .get('./api/plans/' + view.$store.state.plan.id)
                .then(response => {
                    view.$store.state.plan = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        removePlan: function() {
            let view = this;
            if (!confirm('Möchten Sie diesen Plan wirklich löschen?')) {
                return;
            }
            axios
                .delete('./api/plans/' + view.$store.state.plan.id)
                .then(function() {
                    view.$router.push({ path: '/' });
                })
                .catch(error => console.log(error));
        },
        leave(path) {
            if (this.changed) {
                if (
                    confirm(
                        'Möchten Sie das Bearbeiten wirklich verlassen? Ihre Änderungen werden nicht gespeichert.'
                    )
                ) {
                    this.$router.push({ path: path });
                }
            } else {
                this.$router.push({ path: path });
            }
        }
    }
};
</script>
