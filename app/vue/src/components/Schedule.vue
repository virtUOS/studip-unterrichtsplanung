<template>
    <div class="edit-schedule">
        <h1>
            <a href="#" @click="leave('/')"><span class="nav home"></span></a> /
            <a href="#" @click="leave('/plan/' + $store.state.plan.id)">{{ plan.attributes.name }} </a> / Verlaufsplan
        </h1>
        <div class="content-wrapper">
            <div class="schedule-table-wrapper">
                <table class="schedule-table">
                    <tr>
                        <th>
                            <input type="text" v-model="colTitles.col0">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col1">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col2">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col3">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col4">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col5">
                        </th>
                        <th>
                            <input type="text" v-model="colTitles.col6">
                        </th>
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
                <button class="button cancel" @click="leave('/plan/' + $store.state.plan.id)">zurück zur Planübersicht</button>
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
            rows: [],
            colTitles: { col0: 'Zeit', col1: 'Phase', col2: 'Handlungsschritte', col3: 'Methodik', col4: 'Medien', col5: '', col6: '' },
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
            this.$set(this.rows, i, { col0: '', col1: '', col2: '', col3: '', col4: '', col5: '', col6: '' });
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
                        view.createSchedule();
                    }
                    if (response.data.data.length > 0) {
                        view.schedule = response.data.data[0].attributes;
                        let content = JSON.parse(view.schedule.content);
                        if (content.titles != undefined) {
                            view.colTitles = content.titles;
                            delete content.titles;
                        }
                        view.rows = content.rows;
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
                .then(response => {
                    view.schedule = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        storeSchedule() {
            let view = this;
            let content = {
                rows: this.rows,
                titles: this.colTitles
            };

            content = JSON.stringify(content);

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
        leave(path) {
            if (this.changed) {
                if (
                    confirm(
                        'Möchten Sie den Verlaufsplan wirklich verlassen? Ihre Änderungen werden nicht gespeichert.'
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
