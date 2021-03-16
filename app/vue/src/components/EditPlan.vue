<template>
    <div class="edit-plan">
        <h1 v-if="newplan">
            <a href="#" @click="leave('/')"><span class="nav home"></span></a> / Plan hinzufügen
        </h1>

        <h1 v-else>
            <a href="#" @click="leave('/')"><span class="nav home"></span></a> /
            <a href="#" @click="leave('/plan/' + $store.state.plan.id)">{{ this.$store.state.plan.attributes.name }} </a> / Plan bearbeiten
        </h1>

        <div class="content-wrapper">
            <div class="plan-metadata">
                <form class="default" @submit.prevent="preventFormDefault">

                    <fieldset>
                        <legend class="plan-metadata-header" v-if="newplan">
                            {{ getPlanTemplateName(this.$route.params.planType) }}
                        </legend>

                        <legend class="plan-metadata-header" v-else>
                            {{ templatesName }}
                        </legend>

                        <label>
                            <span class="required">
                                Plantitel
                            </span>


                            <input type="text" name="planName" v-model="planName" @change="changed = true"
                                :class="{'invalid': errors.planName}"
                            />

                            <span class="plan-error" v-if="errors.planName">Bitte geben Sie einen Plantitel ein.</span>
                        </label>

                        <label>
                            <span class="required">
                                Schulform
                            </span>

                            <input type="text" name="typeOfSchool" v-model="plan.attributes.metadata.typeOfSchool" @change="changed = true"
                                :class="{'invalid': errors.typeOfSchool}"
                            />
                            <span class="plan-error" v-if="errors.typeOfSchool">Bitte geben Sie eine Schulform an.</span>
                        </label>

                        <label>
                            <span class="required">
                                Klassenstufe
                            </span>

                            <input type="text" name="gradeLevel" v-model="plan.attributes.metadata.gradeLevel" @change="changed = true"
                                :class="{'invalid': errors.gradeLevel}"
                            />
                            <span class="plan-error" v-if="errors.gradeLevel">Bitte geben Sie die Klassenstufe an.</span>
                        </label>

                        <label>
                            <span class="required">
                                Fach
                            </span>

                            <input type="text" name="subject" v-model="plan.attributes.metadata.subject" @change="changed = true"
                                :class="{'invalid': errors.subject}"
                            />
                            <span class="plan-error" v-if="errors.subject">Bitte geben Sie das Fach an.</span>
                        </label>

                        <label>
                            <span class="required">
                                Thema der Unterrichtsstunde
                            </span>

                            <input type="text" name="topic" v-model="plan.attributes.metadata.topic" @change="changed = true"
                                :class="{'invalid': errors.topic}"
                            />
                            <span class="plan-error" v-if="errors.topic">Bitte geben Sie das Thema der Unterrichtsstunde ein.</span>
                        </label>

                        <label>
                            <span class="required">
                                Datum
                            </span>

                            <input type="date" name="date" v-model="plan.attributes.metadata.date" @change="changed = true"
                                :class="{'invalid': errors.date}"
                            />
                            <span class="plan-error" v-if="errors.date">Bitte geben Sie ein Datum ein.</span>
                        </label>

                        <label>
                            <span class="required">
                                Uhrzeit
                            </span>

                            <input type="time" name="time" v-model="plan.attributes.metadata.time" @change="changed = true"
                                :class="{'invalid': errors.time}"
                            />
                            <span class="plan-error" v-if="errors.time">Bitte geben Sie eine Uhrzeit ein.</span>
                        </label>

                    </fieldset>

                    <footer class="plan-metadata-buttons">
                        <button class="button accept" @click="storePlan">Plan speichern</button>
                        <button v-if="!newplan" class="button cancel" @click="leave('/plan/' + $store.state.plan.id)">Abbrechen</button>
                        <button v-if="!newplan" class="button button-remove" @click="removePlan">Plan löschen</button>
                        <button v-if="newplan" class="button cancel" @click="leave('/')">zurück zur Planübersicht</button>
                    </footer>
                </form>
            </div>
            <InfoBox :structureId="structureId" :structureName="structureName" />
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import axios from 'axios';
import mixin from './../mixins/mixin.js';
import { mapGetters } from 'vuex';

export default {
    name: 'EditPlan',
    mixins: [mixin],
    props: ['newplan'],
    components: {
        InfoBox
    },

    computed: {
        ...mapGetters(['plan']),

        structureId() {
            if (this.newplan) {
                return -2;
            }

            return -4;
        },

        structureName() {
            if (this.newplan) {
                return 'Einen Plan erstellen';
            }

            return 'Einen Plan bearbeiten';
        }
    },

    data() {
        return {
            errors: {planName: false, typeOfSchool: false, gradeLevel: false,  subject: false, topic: false, date: false, time: false},
            templatesName: '',
            planName: '',
            changed: false,
            origPlan: {}
        };
    },

    beforeMount() {
        if (this.newplan) {
            this.$store.commit('clearPlan');
        } else {
            this.planName = this.plan.attributes.name;
            this.templatesName = this.getPlanTemplateName(this.plan.attributes.templates_id);
            this.origPlan = this.plan;
        }
    },

    watch:{
        metadata: {
            deep: true,
            handler(val) {
                this.fieldsValid();
            }
        },
        planName: function(val) {
            if (val.trim() != '') this.errors.planName = false;
        }
    },

    methods: {
        preventFormDefault() {
            // console.log('prevent default');
        },

        storePlan: function() {
            if (this.newplan) {
                return this.createPlan();
            } else {
                return this.putPlan();
            }
        },

        fieldsValid: function() {

            let myPlan = this.plan.attributes;

            this.errors.planName     = this.planName.trim() == '';
            this.errors.typeOfSchool = myPlan.metadata.typeOfSchool.trim() == '';
            this.errors.gradeLevel   = myPlan.metadata.gradeLevel.trim() == '';
            this.errors.subject      = myPlan.metadata.subject.trim() == '';
            this.errors.topic        = myPlan.metadata.topic.trim() == '';
            this.errors.date         = myPlan.metadata.date.trim() == '';
            this.errors.time         = myPlan.metadata.time.trim() == '';

            for (let elem in this.errors) {
                if (this.errors[elem]) {
                    // at least on field is invalid
                    return false;
                }
            }

            return true;
        },

        createPlan: function() {
            if (!this.fieldsValid()) {
                return false;
            }

            let myPlan = this.plan.attributes;
            let view = this;

            axios
                .post('./api/plans', {
                    templates_id: this.$route.params.planType,
                    name: view.planName,
                    metadata: JSON.stringify(myPlan.metadata)
                })
                .then(function(response) {
                    let planId = response.data.id;
                    view.$router.push({ path: '/plan/' + planId });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        putPlan: function() {
            if (!this.fieldsValid()) {
                return false;
            }

            let myPlan = this.plan.attributes;
            let view = this;

            axios
                .put('./api/plans/' + myPlan.id, {
                    templates_id: myPlan.templates_id,
                    name: view.planName,
                    metadata: JSON.stringify(myPlan.metadata)
                })
                .then(function() {
                    view.plan.attributes.name = view.planName;
                    view.$router.push({ path: '/plan/' + myPlan.id });
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        removePlan: function() {
            let view = this;

            if (!confirm('Möchten Sie diesen Plan wirklich löschen?')) {
                return false;
            }

            axios
                .delete('./api/plans/' + this.plan.attributes.id)
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
                    this.$store.commit('plan', this.origPlan);
                    this.$router.push({ path: path });
                }
            } else {
                this.$router.push({ path: path });
            }
        }
    }
};
</script>
