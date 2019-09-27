<template>
  <div class="plan-method">
      <h1><router-link to='/'><span class="nav home"></span></router-link> / <router-link :to="'/plan/'+planId">{{plan.title}} </router-link> / Methodik</h1>
      <div class="content-wrapper">
            <div class="content-container">

            </div>
            <InfoBox />
      </div>
  </div>
</template>

<script>
import InfoBox from './InfoBox.vue'

export default {
    name: 'Method',
    components: {
        InfoBox
    },
    computed: {
        plan() {
            return this.$store.state.plan;
        },
        planId() {
                return this.$route.params.planId;
        }
    }
}
</script>