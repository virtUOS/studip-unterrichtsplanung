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
                <button class="button accept" @click="updateSchedule">Verlaufsplan speichern</button>
                <router-link :to="'/plan/' + plan.id">
                    <button class="button cancel">zurück zur Planübersicht</button>
                </router-link>
            </div>
            <InfoBox :title="infoBoxTitle" />
        </div>

    </div>
</template>

<script>
import ScheduleRow from './ScheduleRow.vue';
import InfoBox from './InfoBox.vue';

export default {
    name: 'Schedule',
    components: { ScheduleRow, InfoBox },
    props: {},
    data() {
        return { plan: {}, rows: {}, infoBoxTitle: 'Verlaufsplan'};
    },
    mounted() {
        this.plan = this.$store.state.plan;
        this.rows = this.getRows();
        this.addRow();
    },
    methods: {
        getRows() {
            let rows = {};
            rows[0] = { time: '012', phase: '01', step: '03', method: '0do', media: '0book' };
            rows[1] = { time: '112', phase: '11', step: '13', method: '1do', media: '1book' };
            return rows;
        },
        updateRow(data) {
            let rowId = Object.keys(data)[0];
            this.$set(this.rows, rowId, data[rowId]);
        },
        addRow() {
            let i = parseInt(Object.keys(this.rows)[Object.keys(this.rows).length - 1]) + 1;
            this.$set(this.rows, i, {});
        },
        removeRow(rowId) {
            this.$delete(this.rows, rowId);
        },
        updateSchedule() {
            console.log(this.rows);
        }
    }
};
</script>

<style></style>
