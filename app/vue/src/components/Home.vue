<template>
    <div class="home">
        <h1><span class="nav home"></span></h1>
        <div class="content-wrapper">
            <nav class="homebox homebox-wide plans" v-show="Object.keys(this.plans).length > 0">
                <header>Plan bearbeiten</header>
                <div class="homebox-content">
                    <router-link :to="'/plan/' + plan.id" v-for="plan in this.plans" :key="plan.id">
                        <p class="homebox-link">
                            {{ plan.attributes.name }}
                            <span class="homebox-link-subtitle">
                                - {{ getPlanTemplateName(plan.attributes.templates_id) }}
                            </span>
                            <br />
                            <span class="homebox-link-subtitle plan-mkdate">
                                erstellt am: {{ getFromatedDate(plan.attributes.mkdate) }}
                            </span>
                            <br />
                            <span class="homebox-link-subtitle plan-chdate">
                                geändert am: {{ getFromatedDate(plan.attributes.chdate) }}
                            </span>
                        </p>
                    </router-link>
                </div>
            </nav>
            <nav
                class="homebox add-plan"
                :class="{
                    'homebox-small': Object.keys(this.plans).length > 0,
                    'homebox-fullwidth': Object.keys(this.plans).length === 0
                }"
            >
                <header>Plan hinzufügen</header>
                <div class="homebox-content">
                    <router-link to="/addplan/1"><p class="homebox-link">{{getPlanTemplateName('1')}}</p></router-link>
                    <router-link to="/addplan/2"><p class="homebox-link">{{getPlanTemplateName('2')}}</p></router-link>
                    <router-link to="/addplan/3"><p class="homebox-link">{{getPlanTemplateName('3')}}</p></router-link>
                    <router-link to="/addplan/4"><p class="homebox-link">{{getPlanTemplateName('4')}}</p></router-link>
                </div>
            </nav>
            <InfoBox :structureId="structureId" :structureName="structureName"/>
        </div>
    </div>
</template>

<script>
import InfoBox from './InfoBox.vue';
import mixin from './../mixins/mixin.js';
import { mapGetters } from 'vuex';

export default {
    name: 'Home',
    mixins: [mixin],
    components: {
        InfoBox
    },
    computed: {
        ...mapGetters(['plans', 'infos'])
    },
    data() {
        return {
            structureId: -1,
            structureName: 'Auswählen oder Erstellen'
        };
    },

    mounted() {
        this.$store.dispatch('plans');
        this.$store.dispatch('infos', 1);
    }
};
</script>
