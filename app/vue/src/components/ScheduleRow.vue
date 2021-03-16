<template>
    <tr>
        <td><textarea v-model="col0" @change="updateRow" /></td>
        <td><textarea v-model="col1" @change="updateRow" /></td>
        <td><textarea v-model="col2" @change="updateRow" /></td>
        <td><textarea v-model="col3" @change="updateRow" /></td>
        <td><textarea v-model="col4" @change="updateRow" /></td>
        <td><textarea v-model="col5" @change="updateRow" /></td>
        <td><textarea v-model="col6" @change="updateRow" /></td>
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
            col0: '',
            col1: '',
            col2: '',
            col3: '',
            col4: '',
            col5: '',
            col6: '',
        };
    },
    mounted() {
        this.col0 = this.row.col0;
        this.col1 = this.row.col1;
        this.col2 = this.row.col2;
        this.col3 = this.row.col3;
        this.col4 = this.row.col4;
        this.col5 = this.row.col5;
        this.col6 = this.row.col6;
    },
    methods: {
        updateRow() {
            let newRow = {};
            newRow[this.rowId] = {
                col0: this.col0,
                col1: this.col1,
                col2: this.col2,
                col3: this.col3,
                col4: this.col4,
                col5: this.col5,
                col6: this.col6,
            };
            this.$emit('changeRow', newRow);
        },
        removeRow() {
            this.$emit('removeRow', this.rowId);
        }
    }
};
</script>
