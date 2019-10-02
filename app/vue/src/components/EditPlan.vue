<template>
    <div class="edit-plan">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / Plan bearbeiten
        </h1>
        <div class="content-wrapper">
            <div class="plan-metadata">
                <h3 class="plan-metadata-header">{{ plan.templates_name }}</h3>
                <div class="plan-metadata-fieldset">
                    <label for="planTitle">Plantitel:</label>
                    <input type="text" name="planTitle" v-model="plan.name" /> <br />
                    <label for="typeOfSchool">Schulform:</label>
                    <input type="text" name="typeOfSchool" v-model="plan.metadata.typeOfSchool" /> <br />
                    <label for="gradeLevel">Klassenstufe:</label>
                    <input type="text" name="gradeLevel" v-model="plan.metadata.gradeLevel" /> <br />
                    <label for="subject">Fach:</label>
                    <input type="text" name="subject" v-model="plan.metadata.subject" /> <br />
                    <label for="topic">Thema der Unterrichtsstunde:</label>
                    <input type="text" name="topic" v-model="plan.metadata.topic" /><br />
                    <label for="date">Datum:</label><input type="date" name="date" v-model="plan.metadata.date" />
                    <br />
                    <label for="time">Uhrzeit:</label> <input type="time" name="time" v-model="plan.metadata.time" />
                    <br />
                </div>
                <div class="plan-metadata-buttons">
                    <button class="button" @click="storePlan">Plan speichern</button>
                    <router-link :to="'/plan/' + plan.id"
                        ><button class="button">zurück zur Planübersicht</button></router-link
                    >
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
            plan: {}
        };
    },
    beforeMount() {
        this.plan.metadata = {};
    },
    mounted() {
        this.plan = this.$store.state.plan;
        if (this.plan != undefined) {
            this.plan.metadata = JSON.parse(this.plan.metadata);
            this.plan.templates_name = this.getPlanTemplateName(this.plan.templates_id);
        } else {
            this.plan.metadata = {};
        }
        // this.test();
    },
    computed: {
        infoBoxTitle() {
            return 'Einen Plan bearbeiten';
        }
    },
    methods: {
        storePlan: function() {
            if (!this.plan.name) {
                this.errors.push('Bitte geben Sie einen Plantitel ein!');
                return false;
            } else {
                this.errors = [];
            }
            let view = this;

            axios
                .put('./api/plans/' + this.plan.id, {
                    templates_id: this.plan.templates_id,
                    name: this.plan.name,
                    metadata: JSON.stringify(this.plan.metadata)
                })
                .then(function() {
                    view.$router.push({ path: '/plan/' + view.plan.id });
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
};
</script>
