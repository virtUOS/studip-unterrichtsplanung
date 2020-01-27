<template>
    <div class="edit-schedule">
        <h1>
            <router-link to="/"><span class="nav home"></span></router-link> / Verlaufsplan
        </h1>
        <div class="content-wrapper">
            <div class="schedule-table-wrapper">
                <table class="schedule-table">
                    <tr>
                        <th>Zeit</th>
                        <th>Phase</th>
                        <th>Handlungsschritte</th>
                        <th>Methodik</th>
                        <th>Medien</th>
                        <th class="schedule-header-row-remove"></th>
                    </tr>
                    <ScheduleRow
                        v-for="(row, id) in this.rows"
                        :key="id"
                        :row="row"
                        :rowId="id"
                        @changeRow="updateRow"
                        @removeRow="removeRow"
                    />
                </table>
                <button class="button add" @click="addRow">Zeile hinzufügen</button>
                <br />
                <button class="button accept" @click="storeSchedule">Verlaufsplan speichern</button>
                <button class="button cancel" @click="cancelEdit">zurück zur Planübersicht</button>
            </div>
            <InfoBox :structureId="structureId" :structureName="structureName" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import ScheduleRow from './ScheduleRow.vue';
import InfoBox from './InfoBox.vue';

export default {
    name: 'Schedule',
    components: { ScheduleRow, InfoBox },
    props: {},
    data() {
        return {
            schedule: {},
            rows: {},
            structureId: -5,
            structureName: 'Verlaufsplan',
            changed: false
        };
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        }
    },
    mounted() {
        this.getSchedule();
    },
    methods: {
        updateRow(data) {
            let rowId = Object.keys(data)[0];
            this.$set(this.rows, rowId, data[rowId]);
            this.changed = true;
        },
        addRow() {
            let i = 0;
            let lastKey = parseInt(Object.keys(this.rows)[Object.keys(this.rows).length - 1]);
            if (!isNaN(lastKey)) {
                i = lastKey + 1;
            }
            this.$set(this.rows, i, { time: '', phase: '', step: '', method: '', media: '' });
            this.changed = true;
        },
        removeRow(rowId) {
            this.$delete(this.rows, rowId);
            this.changed = true;
        },
        getSchedule() {
            let view = this;
            axios
                .get('./api/plans/' + view.plan.id + '/schedules')
                .then(response => {
                    if (response.data.data.length == 0) {
                        console.log('create schedule');
                        view.createSchedule();
                    }
                    if (response.data.data.length > 0) {
                        view.schedule = response.data.data[0].attributes;
                        view.rows = JSON.parse(view.schedule.content);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        },
        createSchedule() {
            let view = this;
            axios
                .post('./api/schedules', {
                    content: '',
                    plans_id: view.plan.id
                })
                .then(function() {})
                .catch(error => {
                    console.log(error);
                });
        },
        storeSchedule() {
            let view = this;
            let content = JSON.stringify(this.rows);
            axios
                .put('./api/schedules/' + view.schedule.id, {
                    content: content,
                    plans_id: view.plan.id
                })
                .then(function() {
                    view.$router.push({ path: '/plan/' + view.plan.id });
                })
                .catch(error => console.log(error));
        },
        cancelEdit() {
            if (this.changed) {
                if (
                    confirm(
                        'Möchten Sie den Verlaufsplan wirklich verlassen? Ihre Änderungen werden nicht gespeichert.'
                    )
                ) {
                    this.$router.push({ path: '/plan/' + this.plan.id });
                }
            } else {
                this.$router.push({ path: '/plan/' + this.plan.id });
            }
        }
    }
};
</script>
