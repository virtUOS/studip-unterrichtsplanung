<template>
  <div class="plan-media">
      <h2>Medien für Plan {{planId}}</h2>
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