<template>
  <div class="plan-individual">
      <h2>individuelle Voraussetzungen für Plan {{planId}}</h2>
  </div>
</template>

<script>
export default {
    computed: {
        planId() {
                return this.$route.params.planId;
        }
    }
}
</script>