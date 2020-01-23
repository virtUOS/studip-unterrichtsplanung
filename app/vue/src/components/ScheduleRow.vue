<template>
    <tr>
        <td><input type="text" v-model="time" @change="updateRow" /></td>
        <td><input type="text" v-model="phase" @change="updateRow" /></td>
        <td><input type="text" v-model="step" @change="updateRow" /></td>
        <td><input type="text" v-model="method" @change="updateRow" /></td>
        <td><input type="text" v-model="media" @change="updateRow" /></td>
        <td class="schedule-cell-row-remove">
            <button class="schedule-row-remove" @click="removeRow" title="Zeile entfernen"></button>
        </td>
    </tr>
</template>

<script>
export default {
    name: 'ScheduleRow',
    props: { row: {}, rowId: String },
    data() {
        return {
            time: '',
            phase: '',
            step: '',
            method: '',
            media: ''
        };
    },
    mounted() {
        this.time = this.row.time;
        this.phase = this.row.phase;
        this.step = this.row.step;
        this.method = this.row.method;
        this.media = this.row.media;
    },
    methods: {
        updateRow() {
            let newRow = {};
            newRow[this.rowId] = {
                time: this.time,
                phase: this.phase,
                step: this.step,
                method: this.method,
                media: this.media
            };
            this.$emit('changeRow', newRow);
        },
        removeRow() {
            this.$emit('removeRow', this.rowId);
        }
    }
};
</script>
