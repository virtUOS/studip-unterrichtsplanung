<template>
    <div class="note-element-adder">
        <button class="add-note" :class="{ active: showAdder }" @click="toggleAdderButton">
            <span class="add-note-icon"></span>
            <span class="add-note-text">Textfeld hinzufügen</span>
        </button>
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
        elements: Array,
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
                let pos = Math.max.apply(Math, view.elements.map(element => {return element.attributes.position;}));
                if(pos != -Infinity) {
                    pos = pos + 1;
                } else {
                    pos = 0;
                }
                axios
                    .post('./api/textfields', {
                        structures_id: element.id,
                        text: '',
                        plans_id: view.$store.state.plan.id,
                        position: pos.toString()
                    })
                    .then(function() {
                        view.$emit('addElement', element.id);
                        view.toggleAdderButton();
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
        toggleAdderButton() {
            //event.target.scrollIntoView();
            if (this.showAdder) {
                this.showAdder = false;
            } else {
                this.showAdder = true;
            }
        }
    }
};
</script>
