<template>
    <div class="edit-plan">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / Plan bearbeiten
        </h1>
        <div class="content-wrapper">
            <div class="plan-metadata">
                <h3 class="plan-metadata-header">{{ plan.attributes.templates_name }}</h3>
                <div class="plan-metadata-fieldset">
                    <label for="planTitle">Plantitel:</label>
                    <input type="text" name="planTitle" v-model="plan.attributes.name" /> <br />
                    <label for="typeOfSchool">Schulform:</label>
                    <input type="text" name="typeOfSchool" v-model="plan.attributes.metadata.typeOfSchool" /> <br />
                    <label for="gradeLevel">Klassenstufe:</label>
                    <input type="text" name="gradeLevel" v-model="plan.attributes.metadata.gradeLevel" /> <br />
                    <label for="subject">Fach:</label>
                    <input type="text" name="subject" v-model="plan.attributes.metadata.subject" /> <br />
                    <label for="topic">Thema der Unterrichtsstunde:</label>
                    <input type="text" name="topic" v-model="plan.attributes.metadata.topic" /><br />
                    <label for="date">Datum:</label
                    ><input type="date" name="date" v-model="plan.attributes.metadata.date" />
                    <br />
                    <label for="time">Uhrzeit:</label>
                    <input type="time" name="time" v-model="plan.attributes.metadata.time" />
                    <br />
                </div>
                <div class="plan-metadata-buttons">
                    <button class="button accept" @click="storePlan">Plan speichern</button>
                    <router-link :to="'/plan/' + plan.id">
                        <button class="button cancel">zurück zur Planübersicht</button>
                    </router-link>
                    <button class="button button-remove" @click="removePlan">Plan löschen</button>
                </div>
                <div class="plan-metadata-errors">
                    <p v-for="error in errors" :key="error">{{ error }}</p>
                </div>
            </div>
            <InfoBox />
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
            errors: [],
            plan: {},
            structures_id: -3,
            infoBoxTitle: 'Einen Plan bearbeiten'
        };
    },
    beforeMount() {
        this.plan.attributes = {};
        this.plan.attributes.metadata = {};
    },
    mounted() {
        this.plan = this.$store.state.plan;
        if (this.plan.attributes != undefined) {
            try {
                this.plan.attributes.metadata = JSON.parse(this.plan.attributes.metadata);
            } catch {
                // do nothing
            }
        } else {
            this.plan.attributes.metadata = {};
        }
        this.plan.attributes.templates_name = this.getPlanTemplateName(this.plan.attributes.templates_id);
        this.$store.state.info = {'id': this.structures_id , 'title': this.infoBoxTitle};

    },
    methods: {
        storePlan: function() {
            if (!this.plan.attributes.name) {
                this.errors.push('Bitte geben Sie einen Plantitel ein!');
                return false;
            } else {
                this.errors = [];
            }
            let view = this;

            axios
                .put('./api/plans/' + this.plan.id, {
                    templates_id: this.plan.attributes.templates_id,
                    name: this.plan.attributes.name,
                    metadata: JSON.stringify(this.plan.attributes.metadata)
                })
                .then(function() {
                    view.$router.push({ path: '/plan/' + view.plan.id });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        removePlan: function() {
            let view = this;
            if (!confirm('Möchten Sie diesen Plan wirklich löschen?')) {
                return;
            }
            axios
                .delete('./api/plans/' + this.plan.id)
                .then(function() {
                    view.$router.push({ path: '/' });
                })
                .catch(error => console.log(error));
        }
    }
};
</script>
