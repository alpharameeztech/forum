<template>
    <div>
        <input type="submit" class="btn btn-primary" value="Subscribe" onclick="return confirm('Yes, Subscribe to this product')" :disabled="isDisabled" @click="isDisabled = true; isLoading=true">
        <i v-if="isLoading" class="fa fa-btn fa-spinner fa-spin"></i>
   </div>
</template>

<script>
    export default {
        props: ['plan_id'],

        data: function () {
            return {
                value: this.plan_id,
                isDisabled: false,
                isLoading: false
            }
        },

        mounted() {
            console.log('Component mounted.')
            
        }
    }
</script>
