<template>
    <div class="spinner" v-if="show">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
    props: {
        show: Boolean
    },
    watch:{
        show: function() {
            this.hideSpinner();
        }
    },
    methods:{
        hideSpinner() {
            let view = this;
            setTimeout(function(){ view.$emit('done') }, 2000);
        }
        
    }
}
</script>
