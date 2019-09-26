<template>
  <div class="plan-methods">
      <h2>Methoden für Plan {{planId}}</h2>
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