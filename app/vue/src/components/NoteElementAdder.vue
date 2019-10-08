<template>
    <div class="note-element-adder">
        <button class="add-note" :class="{ active: showAdder }" @click="toggleAdderButton"></button>
        <ul class="note-element-list" v-show="showAdder">
            <li
                v-for="element in elementList"
                :key="element.id"
                @click="addElement(element)"
                :class="{ 'inactive-element': !element.add }"
            >
                {{ element.attributes.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'NoteElementAdder',
    props: {
        elementList: Array,
        structures_id: Number
    },
    data() {
        return {
            showAdder: false
        };
    },
    methods: {
        addElement(element) {
            if (element.add) {
                let view = this;
                axios
                    .post('./api/textfields', {
                        structures_id: element.id,
                        text: '',
                        plan_id: this.$store.state.plan.id
                    })
                    .then(function(response) {
                        view.$emit('addElement', element.id);
                        view.toggleAdderButton();
                        console.log(response);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        toggleAdderButton() {
            if (this.showAdder) {
                this.showAdder = false;
            } else {
                this.showAdder = true;
            }
        }
    }
};
</script>
