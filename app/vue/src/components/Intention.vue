<template>
  <div class="plan-intentions">
      <h2>Intentionalität für Plan {{planId}}</h2>
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