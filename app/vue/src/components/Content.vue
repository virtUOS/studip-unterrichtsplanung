<template>
  <div class="plan-content">
      <h2>Inhalt für Plan {{planId}}</h2>
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